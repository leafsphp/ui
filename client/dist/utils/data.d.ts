import Component from './../core/component';
import { LeafUIConfig } from './../@types/core';
export declare const eventDirectivePrefixRE: () => RegExp;
export declare const rawDirectiveSplitRE: () => RegExp;
export declare const hasDirectiveRE: () => RegExp;
export declare const expressionPropRE: (prop: string) => RegExp;
export declare enum DIRECTIVE_SHORTHANDS {
    '@' = "on",
    ':' = "bind"
}
export declare function arraysMatch(a: any[], b: any[]): boolean;
declare global {
    interface Window {
        leafUI: Component;
        _leafUIConfig: LeafUIConfig;
    }
    interface HTMLElement {
        component: Component;
        compile: () => void;
    }
}
