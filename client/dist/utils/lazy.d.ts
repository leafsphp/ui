/**
 * @author Aiden Bai <hello@aidenybai.com>
 * @package lucia
 */
export declare const lazy: (threshold: number, generatorFunction: () => Generator<undefined, void, unknown>) => (() => void);
export default lazy;
