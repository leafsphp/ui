import { UINode } from './../@types/core';
import { compile } from '../engine/compile';
import render from '../engine/render';
import { directives } from './directives';
import template from './../engine/template';

export default class Component {
    public uiNodes: UINode[] = [];

    constructor() {
        this.uiNodes = [];
    }

    public mount(el: HTMLElement | string) {
        const rootEl =
            el instanceof HTMLElement
                ? el
                : document.querySelector<HTMLElement>(el) || document.body;

        template.findAll(rootEl).map(el => el.compile());
        this.uiNodes = compile(rootEl);

        this.render();

        rootEl['component'] = this;

        return this;
    }

    /**
     * Force renders the DOM based on props
     * @param {string[]=} props - Array of root level properties in state
     * @returns {undefined}
     */
    public render() {
        render(this.uiNodes, directives);
    }
}

export const initComponent = (element: Element) =>
    new Component().mount(element as HTMLElement);
