const blogEditingForm = document.getElementById("blog-editing-form");
let image = '<img src="http://localhost/storage/blog_contents_images/noImage.png">';

blogEditingForm.addEventListener('submit', function() {
    const originalContents = document.getElementById("original-contents");
    const replacementContents = document.getElementById("replacement-contents");
    replacementContents.value = originalContents.innerHTML;
})


// function addImage() {
//     let toolSettingField = document.querySelector('.tool-setting-field');

//     const parentElement = document.createElement("div");
//     parentElement.classList.add("parent-elm");

//     const transitionButton = document.createElement("button");
//     transitionButton.textContent = "移動";
//     parentElement.appendChild(transitionButton);

//     const inputImageField = document.createElement("input");
//     inputImageField.type = "file";
//     parentElement.appendChild(inputImageField);

//     toolSettingField.appendChild(parentElement);
// }




// document.querySelector(".box.drag").draggable = true;
// document.querySelector(".box.drag").addEventListener("dragstart", onDragStart);

// document.querySelectorAll(".box.drop").forEach((element) => {
//     element.addEventListener("drop", onDrop);
//     element.addEventListener("dragover", onDragover);
//     element.addEventListener("dragenter", onDragenter);
//     element.addEventListener("dragleave", onDragleave);
// });

// /**
//  * ドラッグ処理
//  * @param {Event} event 
//  */
// function onDragStart(event) {
//     event.dataTransfer.setData("text", event.currentTarget.id);
// }

// /**
//  * ドロップ処理
//  * @param {Event} event 
//  */
// function onDrop(event) {
//     event.currentTarget.classList.remove("dragging");
//     const boxs = [...document.querySelectorAll(".box")];
//     if (boxs.indexOf(event.currentTarget) === 0) {
//         event.currentTarget.before(document.getElementById(event.dataTransfer.getData("text")));
//     } else {
//         event.currentTarget.after(document.getElementById(event.dataTransfer.getData("text")));
//     }
// }

// /**
//  * 操作が要素上に入ってきたとき
//  * @param {Event} event 
//  */
// function onDragenter(event) {
//     event.currentTarget.classList.toggle("dragging");
// }

// /**
//  * 操作が要素上から出たとき
//  * @param {Event} event 
//  */
// function onDragleave(event) {
//     event.currentTarget.classList.toggle("dragging");
// }

// /**
//  * 操作が要素上を通過してるとき
//  * @param {Event} event 
//  */
// function onDragover(event) {
//     event.preventDefault();
// }


