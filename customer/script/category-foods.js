const foods = [
    {
        name: "Fish",
        category: "fish",
        price: 450,
        description: "Made with Italian Sauce, Chicken, and organic vegetables.",
        image: "images/fish-cultet.jpg"
    },
    {
        name: "Hawaiian Pizza",
        category: "pizza",
        price: 550,
        description: "Delicious pizza with pineapple and ham.",
        image: "images/pizza1.jpg"
    },
    {
        name: "Burger",
        category: "burger",
        price: 350,
        description: "Juicy beef burger with fresh lettuce.",
        image: "images/burger.jpg"
    }
];


// Get category from URL
const urlParams = new URLSearchParams(window.location.search);
const selectedCategory = urlParams.get('category');

const foodContainer = document.getElementById('food-container');

foods
    .filter(food => food.category === selectedCategory)
    .forEach(food => {
        const div = document.createElement('div');
        div.classList.add('food-menu-box');
        div.innerHTML = `
            <div class="food-menu-img">
                <img src="${food.image}" alt="${food.name}" class="img-responsive img-curve">
            </div>
            <div class="food-menu-desc">
                <h4>${food.name}</h4>
                <p class="food-price">ETB-${food.price.toFixed(2)}</p>
                <p class="food-detail">${food.description}</p>
                <br>
                <a href="#" class="btn btn-primary">Order Now</a>
            </div>
        `;
       
        foodContainer.appendChild(div);
    });
    
