export class InputManager {
    textToInput() {
        let text = $(event.target);
        text.hide();
        let input = $('.input__edit', text.parent());
        input.show();
        input.focus();
    }

    inputToText() {
        let input = $(event.target);
        input.hide();
        let text = $('.text__edit', input.parent());
        text.show();

        return input.val();
    }
}