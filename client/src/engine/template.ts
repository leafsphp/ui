export default class template {
    static compileString(str: string) {
        if (str.includes('$eval(')) {
            const evalString = str.match(/\$eval\((.*)\)/)![1];
            return eval(evalString);
        }

        return str;
    }
}
