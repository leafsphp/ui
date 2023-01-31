import { DirectiveData, State, UINode, UINodeType } from './../@types/core';
import { DIRECTIVE_SHORTHANDS, eventDirectivePrefixRE, expressionPropRE } from './data';

export const flattenElementChildren = (
    rootElement: HTMLElement,
    ignoreRootElement = false
): HTMLElement[] => {
    const collection: HTMLElement[] = [];

    if (!ignoreRootElement) {
        collection.push(rootElement);
    }

    for (const childElement of rootElement.children as any) {
        if (childElement instanceof HTMLElement) {
            collection.push(
                ...flattenElementChildren(
                    childElement,
                    childElement.attributes.length === 0
                )
            );
        }
    }

    return collection;
};

export const collectRefs = (
    element: HTMLElement | Document = document
): Record<string, HTMLElement> => {
    const refDirective = 'ui-ref';
    const refElements: NodeListOf<HTMLElement> = element.querySelectorAll(
        `[${refDirective}]`
    );
    const refs: Record<string, HTMLElement> = {};

    refElements.forEach(refElement => {
        const name = refElement.getAttribute(refDirective);

        if (name) {
            refs[name] = refElement;
        }
    });

    return refs;
};

export const collectAndInitDirectives = (
    el: HTMLElement,
    state: State = {}
): [Record<string, DirectiveData>, string[]] => {
    const directives: Record<string, DirectiveData> = {};
    const refs = collectRefs();
    const nodeDeps = [];

    // @ts-ignore
    for (const { name, value } of el.attributes) {
        const isStateDirective = name === 'ui-state';
        const hasDirectivePrefix = name.startsWith('ui-');
        const hasDirectiveShorthandPrefix = Object.keys(
            DIRECTIVE_SHORTHANDS
        ).some(shorthand => name.startsWith(shorthand));

        if (
            isStateDirective ||
            !(hasDirectivePrefix || hasDirectiveShorthandPrefix)
        ) {
            continue;
        }

        const depsInFunctions: string[] = [];
        const propsInState: string[] = Object.keys(state);
        let returnable = true;

        // Finds the dependencies of a directive expression
        const deps: string[] = propsInState.filter(prop => {
            const hasDep = expressionPropRE(prop).test(String(value));

            // Check for dependencies inside functions
            if (hasDep && typeof state[prop] === 'function') {
                const depsInFunction = propsInState.filter(p => {
                    return expressionPropRE(p).test(String(state[prop]));
                });
    
                depsInFunctions.push(...depsInFunction);
            }

            return hasDep;
        });

        if (eventDirectivePrefixRE().test(name)) returnable = false;

        const uniqueCompiledDeps = [...new Set([...deps, ...depsInFunctions])];
        nodeDeps.push(...uniqueCompiledDeps);

        const directiveData = {
            // compute: compute(value, el, returnable, refs),
            compute: () => {},
            deps: uniqueCompiledDeps,
            value
        };

        // Handle normal and shorthand directives=
        const directiveName = hasDirectivePrefix
            ? name.slice('ui-'.length)
            // @ts-ignore
            : `${DIRECTIVE_SHORTHANDS[name[0]]}:${name.slice(1)}`;

        directives[directiveName.toLowerCase()] = directiveData;
    }

    return [directives, [...new Set(nodeDeps)]];
};

export const createASTNode = (
    el: HTMLElement,
    state: State
): UINode | undefined => {
    const [directives, deps] = collectAndInitDirectives(el, state);

    const hasDirectives = Object.keys(directives).length > 0;
    const hasDepInDirectives = Object.values(directives).some(({ value }) =>
        Object.keys(state).some(prop => expressionPropRE(prop).test(value))
    );
    const type = hasDepInDirectives ? UINodeType.DYNAMIC : UINodeType.STATIC;
    const node = { el, deps, directives, type };

    return hasDirectives ? node : undefined;
};

export const compile = (
    el: HTMLElement,
    state: Record<string, unknown>,
    ignoreRootElement = false
): UINode[] => {
    const uiNodes: UINode[] = [];
    const elements = flattenElementChildren(el, ignoreRootElement);

    elements.forEach(element => {
        const newASTNode = createASTNode(element, state);

        if (newASTNode) {
            uiNodes.push(newASTNode);
        }
    });

    return uiNodes;
};
