import { error } from "./error";

export const compute = (
    expression: string,
    el?: HTMLElement,
    returnable = true,
    refs: Record<string, HTMLElement> = {}
): ((state: Record<string, unknown>, event?: Event) => any) => {
    const formattedExpression = `${
        returnable ? `return ${expression}` : expression
    }`;
    const specialPropertiesNames = [
        '$state',
        '$el',
        '$emit',
        '$event',
        '$refs'
    ];

    // This "revives" a function from a string, only using the new Function syntax once during compilation.
    // This is because raw function is ~50,000x faster than new Function
    const computeFunction = new Function(
        `return (${specialPropertiesNames.join(
            ','
        )})=>{with($state){${formattedExpression}}}`
    )();

    const emit = (
        name: string,
        options?: CustomEventInit,
        dispatchGlobal = true
    ) => {
        const event = new CustomEvent(name, options);
        /* istanbul ignore next */
        const target = dispatchGlobal ? window : el || window;
        target.dispatchEvent(event);
    };

    return (state: Record<string, unknown>, event?: Event) => {
        try {
            const value = state[expression];
            if (value) {
                return typeof value === 'function'
                    ? value.bind(state)()
                    : value;
            } else {
                return computeFunction(state, el, emit, event, refs);
            }
        } catch (err) {
            error(err as string, expression, el);
        }
    };
};
