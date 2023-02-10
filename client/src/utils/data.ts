import Component from './../core/component';
import { LeafUIConfig } from './../@types/core';

export const eventDirectivePrefixRE = (): RegExp => /on|@/gim;
export const rawDirectiveSplitRE = (): RegExp => /:|\./gim;

export const hasDirectiveRE = (): RegExp => {
    return new RegExp(
        `(ui-|${Object.keys(DIRECTIVE_SHORTHANDS).join('|')})\\w+`,
        'gim'
    );
};

export const expressionPropRE = (prop: string): RegExp => {
    // Utilizes \b (word boundary) for prop differentiation.
    // Fails when next character is a \w (Word).
    return new RegExp(`\\b${prop}\\b`, 'gim');
};

export enum DIRECTIVE_SHORTHANDS {
    '@' = 'on',
    ':' = 'bind'
}

export function arraysMatch(a: any[], b: any[]) {
    return (
        Array.isArray(a) &&
        Array.isArray(b) &&
        a.length === b.length &&
        a.every((val, index) => val === b[index])
    );
}

declare global {
    interface Window {
        leafUI: {
            rootEl?: HTMLElement;
            component: Component;
        };
        _leafUIConfig: LeafUIConfig;
    }

    interface HTMLElement {
        component: Component;
        compile: () => void;
    }
}

window.leafUI = window.leafUI || {};
