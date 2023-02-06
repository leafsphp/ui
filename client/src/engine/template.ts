export default class template {
    static findAll(el: HTMLElement): HTMLElement[] {
        const elementsWithData = [];

        for (let i = 0; i < el.children.length; i++) {
            const child = el.children[i];
            const html = child.innerHTML;

            (child as HTMLElement).compile = () =>
                template.compile(child as HTMLElement);

            if (/{{(.*?)}}/g.test(html)) {
                elementsWithData.push(child as HTMLElement);
            }
        }

        return elementsWithData;
    }

    static compile(element: HTMLElement): HTMLElement {
        const varsToUpdate = element.textContent!.matchAll(
            /{{\s*\$(\w+)\s*}}/g
        );

        for (const varToUpdate of varsToUpdate) {
            element.textContent = element.textContent!.replace(
                varToUpdate[0],
                window._leafUIConfig?.data?.[varToUpdate[1]] ?? ''
            );
        }

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
        const varsToUpdate = str.matchAll(/{{\s*\$(\w+)\s*}}/g);

        for (const varToUpdate of varsToUpdate) {
            str = str.replace(
                varToUpdate[0],
                window._leafUIConfig?.data?.[varToUpdate[1]] ?? ''
            );
        }

        if (str.includes('$eval(')) {
            const evalString = str.match(/\$eval\((.*)\)/)![1];
            return eval(evalString);
        }

        return str;
    }
}
