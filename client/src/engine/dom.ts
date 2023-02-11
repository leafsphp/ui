import { initComponent } from './../core/component';
import { DIRECTIVE_SHORTHANDS, arraysMatch } from './../utils/data';

export default class Dom {
    static diff(newNode: string, oldNode: HTMLElement): void {
        Dom.diffElements(Dom.getBody(newNode, false), oldNode);
    }

    static diffElements(newNode: HTMLElement, oldNode: HTMLElement): void {
        const newNodes = Array.prototype.slice.call(newNode.children);
        const oldNodes = Array.prototype.slice.call(oldNode.children);

        /**
         * Get the type for a node
         * @param  {Node}   node The node
         * @return {String} The type
         */
        const getNodeType = (node: HTMLElement) => {
            if (node.nodeType === 3) return 'text';
            if (node.nodeType === 8) return 'comment';
            return node.tagName.toLowerCase();
        };

        /**
         * Get the content from a node
         * @param  {Node}   node The node
         * @return {String}      The type
         */
        const getNodeContent = (node: HTMLElement) => {
            if (node.children && node.children.length > 0) return null;
            return node.textContent;
        };

        // If extra elements in DOM, remove them
        let count = oldNodes.length - newNodes.length;
        if (count > 0) {
            for (; count > 0; count--) {
                oldNodes[oldNodes.length - count].parentNode.removeChild(
                    oldNodes[oldNodes.length - count]
                );
            }
        }

        for (let index = 0; index < newNodes.length; index++) {
            const node = newNodes[index];

            console.log('diffing node', node);

            if (!oldNodes[index]) {
                const newNodeClone = node.cloneNode(true);
                oldNode.appendChild(newNodeClone);
                console.log('adding new UI node', newNodeClone);
                initComponent(newNodeClone);
                return;
            }

            if (node instanceof HTMLScriptElement && oldNodes[index] instanceof HTMLScriptElement) {
                if (
                    node.src !== oldNodes[index].src ||
                    node.innerHTML !== oldNodes[index].innerHTML
                ) {
                    const newNodeClone = node.cloneNode(true);
                    oldNodes[index].parentNode.replaceChild(
                        newNodeClone,
                        oldNodes[index]
                    );
                    console.log(
                        'replacing script UI node',
                        newNodeClone,
                        oldNodes[index]
                    );
                }

                continue;
            }

            if (
                arraysMatch(
                    Object.keys(oldNodes[index]?.attributes) ?? [],
                    Object.keys(node.attributes)
                ) &&
                arraysMatch(
                    Object.values(oldNodes[index]?.attributes) ?? [],
                    Object.values(node.attributes)
                ) &&
                oldNodes[index]?.innerHTML === node.innerHTML
            ) {
                console.log('no changes to UI node', oldNodes[index]);
                continue;
            }

            const hasDirectivePrefix = Object.values(oldNodes[index].attributes)
                .map((attr: any) => attr.name.startsWith('ui-'))
                .includes(true);
            const hasDirectiveShorthandPrefix = Object.keys(
                DIRECTIVE_SHORTHANDS
            ).some(shorthand =>
                Object.values(oldNodes[index].attributes)
                    .map((attr: any) => attr.name.startsWith(shorthand))
                    .includes(true)
            );

            if (hasDirectivePrefix || hasDirectiveShorthandPrefix) {
                oldNodes[index].innerHTML = node.innerHTML;

                for (let j = 0; j < node.attributes.length; j++) {
                    const attr = node.attributes[j];

                    if (
                        attr.name.startsWith('ui-') ||
                        Object.keys(DIRECTIVE_SHORTHANDS).some(shorthand =>
                            Object.values(oldNodes[index].attributes)
                                .map((attr: any) =>
                                    attr.name.startsWith(shorthand)
                                )
                                .includes(true)
                        )
                    ) {
                        if (
                            oldNodes[index].getAttribute(attr.name) !==
                            attr.value
                        ) {
                            const newNodeClone = node.cloneNode(true);
                            oldNodes[index].parentNode.replaceChild(
                                newNodeClone,
                                oldNodes[index]
                            );
                            console.log('replacing directive UI node', newNodeClone, oldNodes[index]);
                            initComponent(newNodeClone);
                        }

                        continue;
                    }

                    const newNodeClone = node.cloneNode(true);
                    oldNodes[index].parentNode.replaceChild(
                        newNodeClone,
                        oldNodes[index]
                    );
                    console.log('replacing leaf UI node', newNodeClone, oldNodes[index]);
                    initComponent(newNodeClone);
                }
                continue;
            }

            // If element is not the same type, replace it with new element
            if (getNodeType(node) !== getNodeType(oldNodes[index])) {
                const newNodeClone = node.cloneNode(true);
                oldNodes[index].parentNode.replaceChild(
                    newNodeClone,
                    oldNodes[index]
                );
                console.log('replacing node', newNodeClone, oldNodes[index]);
                initComponent(newNodeClone);
                return;
            }

            // If content is different, update it
            const templateContent = getNodeContent(node);
            if (
                templateContent &&
                templateContent !== getNodeContent(oldNodes[index])
            ) {
                console.log('updating textContent', templateContent, oldNodes[index]);
                oldNodes[index].textContent = templateContent;
            }

            if (
                oldNodes[index].children.length > 0 &&
                node.children.length < 1
            ) {
                console.log('clearing innerHTMl', node, oldNodes[index]);
                oldNodes[index].innerHTML = '';
                continue;
            }

            if (
                oldNodes[index].children.length < 1 &&
                node.children.length > 0
            ) {
                const fragment = document.createDocumentFragment();
                console.log('fragmenting', node, fragment);
                Dom.diff(node, fragment as any);
                oldNodes[index].appendChild(fragment);
                continue;
            }

            if (node.children.length > 0) {
                console.log('diffing children', node, oldNodes[index]);
                Dom.diffElements(node, oldNodes[index]);
            }
        }
    }

    static getBody(html: string, removeScripts: boolean = false): HTMLElement {
        const parser = new DOMParser();
        const dom = parser.parseFromString(html, 'text/html');

        if (removeScripts === true) {
            const scripts = dom.body.getElementsByTagName('script');

            for (let i = 0; i < scripts.length; i++) {
                scripts[i].remove();
            }
        }

        return dom.body;
    }

    static flattenDomIntoArray(node: HTMLElement): HTMLCollection {
        return node.getElementsByTagName('*');
    }

    static compareNodesAndReturnChanges(
        newNode: HTMLElement,
        oldNode: HTMLElement
    ): Record<string, Element | null>[] {
        const newNodes = Dom.flattenDomIntoArray(newNode);
        const oldNodes = Dom.flattenDomIntoArray(oldNode);
        const changes = [];

        for (let i = 0; i < newNodes.length; i++) {
            if (newNodes[i] !== oldNodes[i]) {
                if (newNodes[i].tagName !== oldNodes[i].tagName) {
                    changes.push({
                        oldNode: null,
                        newNode: newNodes[i]
                    });
                } else {
                    changes.push({
                        oldNode: oldNodes[i],
                        newNode: newNodes[i]
                    });
                }
            }
        }

        return changes;
    }
}
