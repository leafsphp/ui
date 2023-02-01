import { UINode } from './../@types/core';
import { compile } from '../engine/compile';
import Connection from '../server/connection';
import render from '../engine/render';
import { directives } from './directives';

export default class Component {
    public uiNodes: UINode[] = [];
    public connection: Connection;

    constructor() {
        this.uiNodes = [];
        this.connection = new Connection();
    }

    public mount(el: HTMLElement | string) {
        const rootEl =
            el instanceof HTMLElement
                ? el
                : document.querySelector<HTMLElement>(el) || document.body;

        this.uiNodes = compile(rootEl);

        this.render();

        // @ts-expect-error
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
