import { error } from '../utils/error';
import Connection from './../server/connection';
import Dom from './dom';

export const compute = (
    expression: string,
    el?: HTMLElement,
    refs: Record<string, HTMLElement> = {}
): ((event?: Event) => any) => {
    const specialPropertiesNames = ['$el', '$emit', '$event', '$refs', '$dom'];

    // This "revives" a function from a string, only using the new Function syntax once during compilation.
    // This is because raw function is ~50,000x faster than new Function
    const computeFunction = new Function(
        `return (${specialPropertiesNames.join(',')}) => {
            const method = ${JSON.stringify(expression)}.split('(')[0];
            const methodArgs = ${JSON.stringify(expression)}.substring(${JSON.stringify(expression)}.indexOf('(') + 1, ${JSON.stringify(expression)}.lastIndexOf(')'));

            if (!window._leafUIConfig.methods.includes(method)) {
                return error(new ReferenceError(method + ' is not defined'), method, $el);
            }

            (${
                Connection.connect
            })('callMethod', { element: $el, method, methodArgs, config: window._leafUIConfig }, $dom);
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
            return computeFunction(el, emit, event, refs, Dom);
        } catch (err) {
            error(err as string, expression, el);
        }
    };
};
