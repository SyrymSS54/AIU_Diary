import eng from "../config/eng.json"
import rus from "../config/rus.json"
import kaz from "../config/kz.json"

class Lang{
    constructor(lang="rus"){
        this.lang = lang

        this.switch_kaz = () => {
            this.lang = "kaz"
        }

        this.switch_rus = () => {
            this.lang = "rus"
        }

        this.switch_eng = () => {
            this.lang = "end"
        }

        this.get_lang = () => {
            switch (this.lang) {
                case "kaz":
                    return kaz
                    break;
                case "rus":
                    return rus
                    break;
                case "eng":
                    return eng;
                    break;
                default:
                    break;
            }
        }
    }
}

export default Lang;