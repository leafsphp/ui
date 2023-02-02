import { error } from '../utils/error';
import Connection from './../server/connection';

export const compute = (
    expression: string,
    el?: HTMLElement,
    refs: Record<string, HTMLElement> = {}
): ((event?: Event) => any) => {
    const specialPropertiesNames = ['$el', '$emit', '$event', '$refs'];

    // This "revives" a function from a string, only using the new Function syntax once during compilation.
    // This is because raw function is ~50,000x faster than new Function
    const computeFunction = new Function(
        `return (${specialPropertiesNames.join(',')}) => {
            const method = ${JSON.stringify(expression)};

            if (!window._leafUIConfig.methods.includes(method)) {
                error(new ReferenceError(method + ' is not defined'), method, $el);
            }

            (${Connection.callMethod})(method, window._leafUIConfig);
        }`
    )();

    const emit = (
        name: string,
        options?: CustomEventInit,
        dispatchGlobal = true
    ) => {
        const event = new CustomEvent(name, options);
        const target = dispatchGlobal ? window : el || window;

        target.dispatchEvent(event);
    };

    return (event?: Event) => {
        try {
            return computeFunction(el, emit, event, refs);
        } catch (err) {
            error(err as string, expression, el);
        }
    };
};
