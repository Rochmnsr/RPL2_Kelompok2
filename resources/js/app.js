import "./bootstrap";
import "preline";

document.addEventListener("livewire:navigated", () => {
    windows.HSStaticMethods.autoInit();
});
