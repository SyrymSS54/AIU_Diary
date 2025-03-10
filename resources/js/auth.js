import * as webix from "webix";
import "webix/skins/flat.css";


webix.ui({
    rows: [
        {},    
        {
            cols: [
                {},    
                {
                    css: "centered_form",
                    view: "form",
                    id: "loginForm",
                    width: 400,
                    elements: [
                        { view: "text", name: "email", label: "Email", type: "email", required: true },
                        { view: "text", name: "password", label: "Пароль", type: "password", required: true },
                        { view: "button", value: "Войти", click: login }
                    ]
                },
                {}    
            ]
        },
        {} 
    ]
});


function login() {
    let form = $$("loginForm");
    if (form.validate()) {
        webix.ajax().post("/signin", {
            _token: document.querySelector('meta[name="csrf-token"]').getAttribute("content"),  
            ...form.getValues()
        }).then(response => {
            webix.message("Успешный вход!");
            
            switch(response.json().route){
                case 'admin':
                    document.location.href = '/admin';
                    break
                case 'teacher':
                    document.location.href = '/teacher';
                    break
                case 'student':
                    document.location.href = '/student';
                    break
                default:
                    webix.message({type:'error', text:"Ошибка входа"})
                    break
            }
        }).catch(error => {
            webix.message({ type: "error", text: "Ошибка входа" });
        });
    }
}
