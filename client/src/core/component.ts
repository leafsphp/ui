import { UINode } from './../@types/core';
import { compile } from './../utils/compile';
import Connection from './connection';

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

        console.log(this.uiNodes, 'uiNodes');

        // this.render();

        // this.connection.connect();

        // rootEl['component'] = this;

        return this;
    }

    public render() {
        this.uiNodes.forEach(node => {
            const { el, directives } = node;
            Object.keys(directives).forEach(directive => {
                const { compute } = directives[directive];
                const value = compute();
                el.setAttribute(directive, value);
            });
        });
    }
}
