export type State = Record<string, unknown>;

export interface DirectiveData {
    compute: (state: Record<string, unknown>, event?: Event) => any;
    value: string;
    deps: string[];
}

export interface UINode {
    directives: Record<string, DirectiveData>;
    deps: string[];
    el: HTMLElement;
    type: UINodeType;
}

export enum UINodeType {
    NULL = -1,
    STATIC = 0,
    DYNAMIC = 1
}
