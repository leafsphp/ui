import { UINode } from './../@types/core';
export default class Component {
    uiNodes: UINode[];
    constructor();
    mount(el: HTMLElement | string): this;
    /**
     * Force renders the DOM based on props
     * @param {string[]=} props - Array of root level properties in state
     * @returns {undefined}
     */
    render(): void;
}
export declare const initComponent: (element: Element) => Component;
