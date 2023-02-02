export type Directives = Record<string, (props: DirectiveProps) => void>;

export interface DirectiveProps {
    el: HTMLElement;
    parts: string[];
    data: DirectiveData;
    node?: UINode;
}

export type KeyedEvent = KeyboardEvent | MouseEvent | TouchEvent;

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

export interface LeafUIConfig {
    el: HTMLElement;
    data: Record<string, any>;
    methods: string[];
    id: string;
    path: string;
    requestMethod: 'GET' | 'POST' | 'PUT' | 'DELETE' | 'PATCH' | 'HEAD';
    component: string;
}
