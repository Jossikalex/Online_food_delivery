const foods=[
    {
id:1,
image:'images/menu-burger.jpg',
title:'food-title',
price:"$2.3",
detail:'made with Italian Sauce,chicken ,and organice vegetable'
},
   {
id:2,
image:'images/menu-momo.jpg',
title:'Smoky Burger',
price:"$2.3",
detail:'made with Italian Sauce,chicken ,and organice vegetable'
},
   {
id:3,
image:'images/menu-pizza.jpg',
title:'Nice Burger',
price:"$2.3",
detail:'made with Italian Sauce,chicken ,and organice vegetable'
},{
id:1,
image:'images/menu-burger.jpg',
title:'food-title',
price:"$2.3",
detail:'made with Italian Sauce,chicken ,and organice vegetable'
},
   {
id:2,
image:'images/menu-burger.jpg',
title:'Smoky Burger',
price:"$2.3",
detail:'made with Italian Sauce,chicken ,and organice vegetable'
},
   {
id:3,
image:'images/menu-momo.jpg',
title:'Nice Burger',
price:"$2.3",
detail:'made with Italian Sauce,chicken ,and organice vegetable'
},{
    id:1,
image:'images/menu-burger.jpg',
title:'food-title',
price:"$2.3",
detail:'made with Italian Sauce,chicken ,and organice vegetable'

},{
    id:2,
image:'images/menu-momo.jpg',
title:'Smoky Burger',
price:"$2.3",
detail:'made with Italian Sauce,chicken ,and organice vegetable'

},{
    id:3,
image:'images/menu-pizza.jpg',
title:'Nice Burger',
price:"$2.3",
detail:'made with Italian Sauce,chicken ,and organice vegetable'
},{
    id:3,
image:'images/menu-pizza.jpg',
title:'Nice Burger',
price:"$2.3",
detail:'made with Italian Sauce,chicken ,and organice vegetable'
},{
    id:3,
image:'images/menu-pizza.jpg',
title:'Nice Burger',
price:"$2.3",
detail:'made with Italian Sauce,chicken ,and organice vegetable'
},{
    id:3,
image:'images/menu-pizza.jpg',
title:'Nice Burger',
price:"$2.3",
detail:'made with Italian Sauce,chicken ,and organice vegetable'
},{
        id:2,
image:'images/menu-momo.jpg',
title:'Smoky Burger',
price:"$2.3",
detail:'made with Italian Sauce,chicken ,and organice vegetable'
},{
        id:2,
image:'images/menu-momo.jpg',
title:'Smoky Burger',
price:"$2.3",
detail:'made with Italian Sauce,chicken ,and organice vegetable'
},{
        id:2,
image:'images/menu-momo.jpg',
title:'Smoky Burger',
price:"$2.3",
detail:'made with Italian Sauce,chicken ,and organice vegetable'
}

];
let foodsHtml =' ';
foods.forEach((items)=>{
foodsHtml +=`    <div class="food-menu-box">
                <div class="food-menu-img">
                    <img src="${items.image}" alt="Chicke Hawain Pizza" class="img-responsive img-curve">
                </div>

                <div class="food-menu-desc">
                    <h4>${items.title}</h4>
                    <p class="food-price">${items.price}</p>
                    <p class="food-detail">
                        ${items.detail}
                    </p>
                    <br>

                    <a href="order.html" class="btn btn-primary">Add to Cart</a>
                </div>
            </div>`
console.log(foodsHtml);

});
document.querySelector('.js-foods-html').innerHTML = foodsHtml;
