export default class Dom {
    static diff(newNode: string, oldNode: HTMLElement): void;
    static getBody(html: string, removeScripts?: boolean): HTMLElement;
    static flattenDomIntoArray(node: HTMLElement): HTMLCollection;
    static compareNodesAndReturnChanges(newNode: HTMLElement, oldNode: HTMLElement): Record<string, Element | null>[];
}
