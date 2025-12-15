const categories = [
    {
        name: "Fish",
        image: "images/fish-cultet.jpg",
        link: "category-foods.html?category=fish"
    },
    {
        name: "Pizza",
        image: "images/pizza.jpg",
        link: "category-foods.html?category=pizza"
    },
    {
        name: "Burger",
        image: "images/burger.jpg",
        link: "category-foods.html?category=burger"
    }
];
console.log("Categories data:", categories);
const categoryContainer = document.getElementById('category-container');
categories.forEach(cat => {
    const a = document.createElement('a');
    a.href = cat.link;
    a.innerHTML = `
        <div class="box-3 float-container">
            <img src="${cat.image}" alt="${cat.name}" class="img-responsive img-curve">
            <h3 class="float-text text-white">${cat.name}</h3>
        </div>
    `;
    
    categoryContainer.appendChild(a);
   categories.forEach(cat => {
    console.log("Generating category:", cat.name);
});
   
});


 
