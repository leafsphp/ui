import { DirectiveData, UINode, UINodeType } from '../@types/core';
import { compute } from './compute';
import { DIRECTIVE_SHORTHANDS } from '../utils/data';

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

export const initDirectives = (
    el: HTMLElement
): Record<string, DirectiveData> => {
    const directives: Record<string, DirectiveData> = {};
    const refs = collectRefs();

    // @ts-ignore
    for (const { name, value } of el.attributes) {
        const hasDirectivePrefix = name.startsWith('ui-');
        const hasDirectiveShorthandPrefix = Object.keys(
            DIRECTIVE_SHORTHANDS
        ).some(shorthand => name.startsWith(shorthand));

        if (!(hasDirectivePrefix || hasDirectiveShorthandPrefix)) {
            continue;
        }

        const directiveData = {
            compute: compute(value, el, refs),
            value
        };

        // Handle normal and shorthand directives=
        const directiveName = hasDirectivePrefix
            ? name.slice('ui-'.length)
            : // @ts-ignore
              `${DIRECTIVE_SHORTHANDS[name[0]]}:${name.slice(1)}`;

        directives[directiveName.toLowerCase()] = directiveData;
    }

    return directives;
};

export const createASTNode = (el: HTMLElement): UINode | undefined => {
    const directives = initDirectives(el);
    const hasDirectives = Object.keys(directives).length > 0;
    const node = { el, directives, type: UINodeType.STATIC };

    return hasDirectives ? node : undefined;
};

export const compile = (
    el: HTMLElement,
    ignoreRootElement = false
): UINode[] => {
    const uiNodes: UINode[] = [];
    const elements = flattenElementChildren(el, ignoreRootElement);

    elements.forEach(element => {
        const newASTNode = createASTNode(element);

        if (newASTNode) {
            uiNodes.push(newASTNode);
        }
    });

    return uiNodes;
};
