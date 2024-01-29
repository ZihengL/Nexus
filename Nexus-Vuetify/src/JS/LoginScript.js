// LoginScript.js

const loginScript = {
  init(vm) {
    const loginText = document.querySelector("div.login");
    const loginForm = document.querySelector("form.login");
    const loginBtn = document.querySelector("label.login");
    const signupBtn = document.querySelector("label.signup");
    const signupLink = document.querySelector("form .signup-link a");

    signupBtn.addEventListener("click", () => {
      vm.toggleSignup();
      loginForm.style.marginLeft = "-50%";
      loginText.style.marginLeft = "-50%";
    });

    loginBtn.addEventListener("click", () => {
      vm.toggleLogin();
      loginForm.style.marginLeft = "0%";
      loginText.style.marginLeft = "0%";
    });

    signupLink.addEventListener("click", (event) => {
      vm.toggleSignup();
      event.preventDefault();
    });
  },
};

export default loginScript;
