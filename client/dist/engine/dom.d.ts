export default class Dom {
    /**
     * Get the body of an HTML string
     *
     * @param html The html to parse
     * @param removeScripts Whether to remove scripts from the html
     * @returns The body of the html
     */
    static getBody(html: string, removeScripts?: boolean): HTMLElement;
    /**
     * Wrap DOM node with a template element
     */
    static wrap(node: Node): HTMLElement;
    /**
     * Parse string to DOM
     *
     * @param html The html to parse
     */
    static parse(html: string): HTMLElement;
    /**
     * Get the type for a node
     * @param  {HTMLElement} node The node
     * @return {String} The type
     */
    static getNodeType(node: HTMLElement): string;
    /**
     * Get the content from a node
     * @param  {Node}   node The node
     * @return {String}      The type
     */
    static getNodeContent(node: HTMLElement): string | null;
    /**
     * Diff the DOM from a string and an element
     *
     * @param newNode The new node
     * @param oldNode The old node
     * @returns The diffed node
     */
    static diff(newNode: string, oldNode: HTMLElement): void;
    /**
     * Diff the DOM from two elements
     *
     * @param newNode The new node
     * @param oldNode The old node
     * @returns The diffed node
     */
    static diffElements(newNode: HTMLElement, oldNode: HTMLElement): void;
}
