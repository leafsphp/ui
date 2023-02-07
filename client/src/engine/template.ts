export default class template {
    static findAll(el: HTMLElement): HTMLElement[] {
        const elementsWithData = [];

        for (let i = 0; i < el.children.length; i++) {
            const child = el.children[i];
            const html = child.innerHTML;

            (child as HTMLElement).compile = () =>
                template.compile(child as HTMLElement);

            if (/\$eval\(.*?\)/.test(html)) {
                elementsWithData.push(child as HTMLElement);
            }
        }

        return elementsWithData;
    }

    static compile(element: HTMLElement): HTMLElement {
        const itemsToEval = element.textContent!.matchAll(/\$eval\((.*)\)/g);
        
        for (const itemToEval of itemsToEval) {
            element.textContent = element.textContent!.replace(
                itemToEval[0],
                eval(itemToEval[1]) ?? ''
            );
        }

        element.textContent = element.textContent!.replace(/{{(.*?)}}/g, '$1') ?? element.textContent;

        return element;
    }

    static compileString(str: string) {
        if (str.includes('$eval(')) {
            const evalString = str.match(/\$eval\((.*)\)/)![1];
            return eval(evalString);
        }

        return str;
    }
}
