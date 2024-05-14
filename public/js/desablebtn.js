let focus = document.querySelector(".focus");
let focusButton = document.querySelector(".button");

focusButton.disabled = true; //setting button state to disabled

focus.addEventListener("input", stateHandle);

function stateHandle() {
   if (focus.value.trim() === "") {
      focusButton.disabled = true; //button remains disabled
   } else {
      focusButton.disabled = false; //button is enabled
   }
}

if (focus.value.trim() !== "") {
   focusButton.disabled = false
}
