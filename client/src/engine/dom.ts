import { DIRECTIVE_SHORTHANDS } from './../utils/data';

export default class Dom {
    static diff(newNode: string, oldNode: HTMLElement): void {
        const newDomBody = Dom.getBodyWithoutScripts(newNode);
        const diff = Dom.compareNodesAndReturnChanges(newDomBody, oldNode);

        console.log(oldNode, newDomBody, 'doms');

        for (let i = 0; i < diff.length; i++) {
            if (
                diff[i] instanceof HTMLScriptElement ||
                diff[i].oldNode.children.length !== 0
            ) {
                continue;
            }

            const hasDirectivePrefix = Object.values(diff[i].oldNode.attributes)
                .map(attr => attr.name.startsWith('ui-'))
                .includes(true);
            const hasDirectiveShorthandPrefix = Object.keys(
                DIRECTIVE_SHORTHANDS
            ).some(shorthand =>
                Object.values(diff[i].oldNode.attributes)
                    .map(attr => attr.name.startsWith(shorthand))
                    .includes(true)
            );

            if (hasDirectivePrefix || hasDirectiveShorthandPrefix) {
                diff[i].oldNode.innerHTML = diff[i].newNode.innerHTML;

                for (let j = 0; j < diff[i].newNode.attributes.length; j++) {
                    const attr = diff[i].newNode.attributes[j];

                    if (
                        attr.name.startsWith('ui-') ||
                        Object.keys(DIRECTIVE_SHORTHANDS).some(shorthand =>
                            Object.values(diff[i].oldNode.attributes)
                                .map(attr => attr.name.startsWith(shorthand))
                                .includes(true)
                        )
                    ) {
                        continue;
                    }

                    diff[i].oldNode.setAttribute(attr.name, attr.value);
                }

                continue;
            }

            diff[i].oldNode.replaceWith(diff[i].newNode);
        }
    }

    static getBodyWithoutScripts(html: string): HTMLElement {
        const parser = new DOMParser();
        const dom = parser.parseFromString(html, 'text/html');
        const scripts = dom.getElementsByTagName('script');

        for (let i = 0; i < scripts.length; i++) {
            scripts[i].remove();
        }

        return dom.body;
    }

    static flattenDomIntoArray(node: HTMLElement): HTMLCollection {
        return node.getElementsByTagName('*');
    }

    static compareNodesAndReturnChanges(
        newNode: HTMLElement,
        oldNode: HTMLElement
    ): Record<string, Element>[] {
        const newNodes = Dom.flattenDomIntoArray(newNode);
        const oldNodes = Dom.flattenDomIntoArray(oldNode);
        const changes = [];

        for (let i = 0; i < newNodes.length; i++) {
            if (newNodes[i] !== oldNodes[i]) {
                changes.push({
                    oldNode: oldNodes[i],
                    newNode: newNodes[i]
                });
            }
        }

        return changes;
    }
}
