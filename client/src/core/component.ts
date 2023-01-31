import { State, UINode } from './../@types/core';
import { compile } from './../utils/compile';
import Connection from './connection';

export default class Component {
    protected state: State = Object.create(null);
    protected uiNodes: UINode[] = [];
    protected connection: Connection;

    constructor(state: State) {
        this.state = state;
        this.uiNodes = [];
        this.connection = new Connection();
    }

    public mount(el: HTMLElement | string) {
        // const finalState = { ...this.state, $render: this.render.bind(this) };
        const rootEl =
            el instanceof HTMLElement
                ? el
                : document.querySelector<HTMLElement>(el) || document.body;

        // compile and handle directives and stuff
        this.uiNodes = compile(rootEl, this.state);

        console.log(this.uiNodes, 'uiNodes');

        // make state reactive
        // this.state = reactive(finalState, this.render.bind(this));

        // this.render();

        // this.connection.connect(this.state);

        // rootEl['component'] = this;

        return this;
    }

    public render() {
        this.uiNodes.forEach(node => {
            const { el, directives } = node;
            Object.keys(directives).forEach(directive => {
                const { compute } = directives[directive];
                const value = compute(this.state);
                el.setAttribute(directive, value);
            });
        });
    }
}
