const ul = document.querySelector("ul");

async function data() {
  try {
    const response = await fetch("../php/profile.php");
    const data = await response.json();
    data?.forEach((element) => {
      const newItem = document.createElement("li");
      newItem.textContent = element;
      ul.appendChild(newItem);
    });

    console.log(data);
  } catch (error) {
    console.log(error);
  }
}
data();
