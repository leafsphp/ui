export default class Dom {
    static diff(newNode: string, oldNode: HTMLElement): void;
    static getBodyWithoutScripts(html: string): HTMLElement;
    static flattenDomIntoArray(node: HTMLElement): HTMLCollection;
    static compareNodesAndReturnChanges(newNode: HTMLElement, oldNode: HTMLElement): Record<string, Element>[];
}
