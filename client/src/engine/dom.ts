import { initComponent } from './../core/component';
import { arraysMatch } from './../utils/data';

export default class Dom {
    /**
     * Get the body of an HTML string
     *
     * @param html The html to parse
     * @param removeScripts Whether to remove scripts from the html
     * @returns The body/root of the html
     */
    public static getBody(
        html: string,
        removeScripts: boolean = false,
        nodeToReturn: 'body' | 'root' = 'body'
    ): HTMLElement {
        const parser = new DOMParser();
        const dom = parser.parseFromString(html, 'text/html');

        if (removeScripts === true) {
            const scripts = dom.body.getElementsByTagName('script');

            for (let i = 0; i < scripts.length; i++) {
                scripts[i].remove();
            }
        }

        return nodeToReturn === 'body' ? dom.body : dom.documentElement;
    }

    /**
     * Wrap DOM node with a template element
     */
    public static wrap(node: Node): HTMLElement {
        const wrapper = document.createElement('x-leafui-wrapper');
        wrapper.appendChild(node);
        return wrapper;
    }

    /**
     * Parse string to DOM
     *
     * @param html The html to parse
     */
    public static parse(html: string): HTMLElement {
        const parser = new DOMParser();
        const dom = parser.parseFromString(html, 'text/html');

        return dom.getRootNode().firstChild as HTMLElement;
    }

    /**
     * Get the type for a node
     * @param  {HTMLElement} node The node
     * @return {String} The type
     */
    public static getNodeType(node: HTMLElement): string {
        if (node.nodeType === 3) return 'text';
        if (node.nodeType === 8) return 'comment';
        return node.tagName.toLowerCase();
    }

    /**
     * Get the content from a node
     * @param  {Node}   node The node
     * @return {String}      The type
     */
    public static getNodeContent(node: HTMLElement) {
        if (node.children && node.children.length > 0) return null;
        return node.textContent;
    }

    /**
     * Diff the DOM from a string and an element
     *
     * @param newNode The new node
     * @param oldNode The old node
     * @returns The diffed node
     */
    public static diff(newNode: string, oldNode: HTMLElement): void {
        if (newNode.includes('<html')) {
            if (typeof window !== 'undefined') {
                oldNode = window.document.documentElement;
            }
        }

        const structuredNewNode =
            oldNode instanceof HTMLHtmlElement
                ? Dom.getBody(newNode, false, 'root')
                : oldNode.nodeName === 'BODY'
                ? Dom.getBody(newNode, false)
                : Dom.getBody(newNode, true).children[0];
        const structuredOldNode = oldNode;

        Dom.diffElements(structuredNewNode as HTMLElement, structuredOldNode);
    }

    /**
     * Diff the DOM from two elements
     *
     * @param newNode The new node
     * @param oldNode The old node
     * @returns The diffed node
     */
    public static diffElements(
        newNode: HTMLElement,
        oldNode: HTMLElement
    ): void {
        const newNodes = Array.prototype.slice.call(newNode.children);
        const oldNodes = Array.prototype.slice.call(oldNode.children);

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

            if (!oldNodes[index]) {
                const newNodeClone = node.cloneNode(true);
                oldNode.appendChild(newNodeClone);
                initComponent(newNodeClone);
                continue;
            }

            if (
                node instanceof HTMLScriptElement &&
                oldNodes[index] instanceof HTMLScriptElement
            ) {
                if (
                    node.src !== oldNodes[index].src ||
                    node.innerHTML !== oldNodes[index].innerHTML
                ) {
                    const newNodeClone = node.cloneNode(true);
                    oldNodes[index].parentNode.replaceChild(
                        newNodeClone,
                        oldNodes[index]
                    );
                }

                continue;
            }

            if (
                !arraysMatch(
                    Object.values(node.parentNode?.attributes ?? {}),
                    Object.values(oldNodes[index].parentNode?.attributes ?? {})
                )
            ) {
                for (
                    let nIndex = 0;
                    nIndex < node.parentNode.attributes?.length;
                    nIndex++
                ) {
                    const attribute = node.parentNode.attributes[nIndex];
                    oldNodes[index]?.parentNode?.setAttribute(
                        attribute.name,
                        attribute.value
                    );
                }
            }

            if (
                Dom.getNodeType(node) !== Dom.getNodeType(oldNodes[index]) ||
                !arraysMatch(
                    Object.keys(oldNodes[index]?.attributes) ?? [],
                    Object.keys(node.attributes)
                ) ||
                oldNodes[index]?.innerHTML !== node.innerHTML
            ) {
                const newNodeClone = node.cloneNode(true);

                if (!oldNodes[index].parentNode) {
                    oldNodes[index].replaceWith(newNodeClone);
                    initComponent(newNodeClone);
                } else {
                    oldNodes[index].parentNode.replaceChild(
                        newNodeClone,
                        oldNodes[index]
                    );
                    initComponent(newNodeClone);
                }

                continue;
            }

            // If content is different, update it
            const templateContent = Dom.getNodeContent(node);
            if (
                templateContent &&
                templateContent !== Dom.getNodeContent(oldNodes[index])
            ) {
                oldNodes[index].textContent = templateContent;
            }

            if (
                oldNodes[index].children.length > 0 &&
                node.children.length < 1
            ) {
                oldNodes[index].innerHTML = '';
                continue;
            }

            if (
                oldNodes[index].children.length < 1 &&
                node.children.length > 0
            ) {
                const fragment = document.createDocumentFragment();
                Dom.diffElements(node, fragment as any);
                oldNodes[index].appendChild(fragment);
                continue;
            }

            if (node.children.length > 0) {
                Dom.diffElements(node, oldNodes[index]);
            }
        }
    }
}
