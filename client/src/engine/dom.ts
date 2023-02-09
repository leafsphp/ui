import { initComponent } from './../core/component';

export default class Dom {
    static diff(newNode: string, oldNode: HTMLElement): void {
        const newDomBody = Dom.getBody(newNode, true);
        const newNodes = Array.prototype.slice.call(newDomBody.childNodes);
        const oldNodes = Array.prototype.slice.call(oldNode.childNodes);

        /**
         * Get the type for a node
         * @param  {Node}   node The node
         * @return {String}      The type
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
            if (node.childNodes && node.childNodes.length > 0) return null;
            return node.textContent;
        };

        // const diff = Dom.compareNodesAndReturnChanges(newDomBody, oldNode);

        // If extra elements in DOM, remove them
        var count = oldNodes.length - newNodes.length;
        if (count > 0) {
            for (; count > 0; count--) {
                oldNodes[oldNodes.length - count].parentNode.removeChild(
                    oldNodes[oldNodes.length - count]
                );
            }
        }

        // Diff each item in the newNodes
        newNodes.forEach(function(node, index) {
            // If element doesn't exist, create it
            if (!oldNodes[index]) {
                const newNodeClone = node.cloneNode(true);
                oldNode.appendChild(newNodeClone);
                initComponent(newNodeClone);
                return;
            }

            // If element is not the same type, replace it with new element
            if (getNodeType(node) !== getNodeType(oldNodes[index])) {
                const newNodeClone = node.cloneNode(true);
                oldNodes[index].parentNode.replaceChild(
                    newNodeClone,
                    oldNodes[index]
                );
                initComponent(newNodeClone);
                return;
            }

            // If content is different, update it
            const templateContent = getNodeContent(node);
            if (
                templateContent &&
                templateContent !== getNodeContent(oldNodes[index])
            ) {
                oldNodes[index].textContent = templateContent;
            }

            if (
                oldNodes[index].childNodes.length > 0 &&
                node.childNodes.length < 1
            ) {
                oldNodes[index].innerHTML = '';
                return;
            }

            if (
                oldNodes[index].childNodes.length < 1 &&
                node.childNodes.length > 0
            ) {
                var fragment = document.createDocumentFragment();
                Dom.diff(node, fragment as any);
                oldNodes[index].appendChild(fragment);
                initComponent(node);
                return;
            }

            if (node.childNodes.length > 0) {
                Dom.diff(node, oldNodes[index]);
                initComponent(node);
            }
        });

        // console.log(futureNodes);
    }

    static getBody(html: string, removeScripts: boolean = false): HTMLElement {
        const parser = new DOMParser();
        const dom = parser.parseFromString(html, 'text/html');

        if (removeScripts) {
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
