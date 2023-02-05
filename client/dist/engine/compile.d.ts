import { DirectiveData, UINode } from '../@types/core';
export declare const flattenElementChildren: (rootElement: HTMLElement, ignoreRootElement?: boolean) => HTMLElement[];
export declare const collectRefs: (element?: HTMLElement | Document) => Record<string, HTMLElement>;
export declare const initDirectives: (el: HTMLElement) => Record<string, DirectiveData>;
export declare const createASTNode: (el: HTMLElement) => UINode | undefined;
export declare const compile: (el: HTMLElement, ignoreRootElement?: boolean) => UINode[];
