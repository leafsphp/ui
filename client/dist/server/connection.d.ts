import Dom from './../engine/dom';
export default class Connection {
    protected static headers: Record<string, string>;
    static connect(type: string, uiData: Record<string, any>, dom: typeof Dom): Promise<void>;
}
