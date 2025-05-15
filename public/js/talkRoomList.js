let one = document.getElementById("test1");
let two = document.querySelector(".talk-room-list1");
// let two = one.cloneNode(one)

let result = one.isEqualNode(two);
console.log(result);