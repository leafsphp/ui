export interface DirectiveData {
    compute: (event?: Event) => any;
    value: string;
}

export interface UINode {
    directives: Record<string, DirectiveData>;
    el: HTMLElement;
    type: UINodeType;
}

export enum UINodeType {
    NULL = -1,
    STATIC = 0,
    DYNAMIC = 1
}
