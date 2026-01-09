<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run()
    {
        // Clear existing data to avoid duplicates
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        MenuItem::truncate();
        MenuCategory::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $categories = [
            'Signature Burgers' => [
                'image' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=800&q=80',
                'items' => [
                    ['title' => 'The Street King', 'price' => 1850, 'description' => 'Triple beef patty, triple cheese, crispy bacon, and our secret sauce.'],
                    ['title' => 'Ghost Pepper Inferno', 'price' => 1650, 'description' => 'Spicy beef patty with ghost pepper sauce, jalapeños, and pepper jack cheese.'],
                    ['title' => 'Truffle Mushroom Swiss', 'price' => 1950, 'description' => 'Wagyu beef, sautéed mushrooms, truffle aioli, and melted Swiss cheese.'],
                    ['title' => 'BBQ Bacon Beast', 'price' => 1750, 'description' => 'Double beef, honey BBQ sauce, onion rings, and smoked bacon.'],
                    ['title' => 'The Breakfast Burger', 'price' => 1550, 'description' => 'Beef patty topped with a fried egg, hash brown, and maple syrup.'],
                    ['title' => 'Monster Mac', 'price' => 1800, 'description' => 'Four beef patties, special mac sauce, lettuce, and pickles.'],
                    ['title' => 'Blueberry BBQ Burger', 'price' => 1700, 'description' => 'Unique beef patty with blueberry compote, goat cheese, and arugula.'],
                    ['title' => 'California Dreamin', 'price' => 1600, 'description' => 'Beef patty, fresh avocado, sprouts, tomato, and lime crema.'],
                    ['title' => 'Bourbon Glazed Delight', 'price' => 1900, 'description' => 'Beef patty infused with bourbon glaze, caramelized onions, and brie.'],
                    ['title' => 'The Big Kahuna', 'price' => 1650, 'description' => 'Beef patty, grilled pineapple, teriyaki glaze, and ham.'],
                ]
            ],
            'Classic Beef' => [
                'image' => 'https://images.unsplash.com/photo-1571091718767-18b5b1457add?auto=format&fit=crop&w=800&q=80',
                'items' => [
                    ['title' => 'Classic Cheeseburger', 'price' => 950, 'description' => 'Simple and delicious beef patty with American cheese.'],
                    ['title' => 'Double Quarter Pounder', 'price' => 1350, 'description' => 'Two 1/4 lb beef patties with onion and pickles.'],
                    ['title' => 'Bacon Deluxe', 'price' => 1450, 'description' => 'Beef, bacon, lettuce, tomato, and mayo.'],
                    ['title' => 'Mushroom Burger', 'price' => 1250, 'description' => 'Beef patty with earthy sautéed mushrooms.'],
                    ['title' => 'Onion Crunch Burger', 'price' => 1150, 'description' => 'Beef patty with fried onion strings and mustard.'],
                    ['title' => 'Steakhouse Special', 'price' => 1500, 'description' => 'Thick beef patty with steak sauce and blue cheese.'],
                    ['title' => 'Texas Ranger', 'price' => 1400, 'description' => 'Beef patty, chili, and cheddar cheese.'],
                    ['title' => 'Garden Beef Burger', 'price' => 1200, 'description' => 'Beef patty loaded with extra fresh vegetables.'],
                    ['title' => 'Smoky Joe', 'price' => 1300, 'description' => 'Beef patty with liquid smoke seasoning and provolone.'],
                    ['title' => 'Peanut Butter Bacon', 'price' => 1550, 'description' => 'Unexpectedly delicious beef with creamy PB and bacon.'],
                ]
            ],
            'Chicken Burgers' => [
                'image' => 'https://images.unsplash.com/photo-1610614819513-58e34989848b?auto=format&fit=crop&w=800&q=80',
                'items' => [
                    ['title' => 'Zesty Lemon Chicken', 'price' => 1100, 'description' => 'Grilled chicken breast with lemon herb seasoning.'],
                    ['title' => 'Nashville Hot Chicken', 'price' => 1350, 'description' => 'Fried chicken dipped in spicy oil with coleslaw.'],
                    ['title' => 'Chicken Avocado Club', 'price' => 1450, 'description' => 'Grilled chicken, avocado, and ranch dressing.'],
                    ['title' => 'Honey Mustard Crunch', 'price' => 1250, 'description' => 'Crispy chicken with sweet honey mustard sauce.'],
                    ['title' => 'Buffalo Chicken Burger', 'price' => 1300, 'description' => 'Fried chicken tossed in buffalo sauce with ranch.'],
                    ['title' => 'Chicken Parm Burger', 'price' => 1400, 'description' => 'Crispy chicken, marinara, and mozzarella.'],
                    ['title' => 'Oriental Ginger Chicken', 'price' => 1200, 'description' => 'Grilled chicken with soy ginger glaze.'],
                    ['title' => 'Malibu Chicken', 'price' => 1500, 'description' => 'Chicken patty topped with ham and Swiss cheese.'],
                    ['title' => 'Satay Chicken Burger', 'price' => 1350, 'description' => 'Grilled chicken with spicy peanut sauce.'],
                    ['title' => 'Mediterranean Chicken', 'price' => 1450, 'description' => 'Chicken with feta, olives, and tzatziki.'],
                ]
            ],
            'Sides & Fries' => [
                'image' => 'https://images.unsplash.com/photo-1573012676755-be2361ac86b4?auto=format&fit=crop&w=800&q=80',
                'items' => [
                    ['title' => 'Regular Fries', 'price' => 350, 'description' => 'Classic skin-on salted fries.'],
                    ['title' => 'Curly Fries', 'price' => 450, 'description' => 'Seasoned spiral-cut fries.'],
                    ['title' => 'Sweet Potato Fries', 'price' => 550, 'description' => 'Crispy and sweet with marshmallow dip.'],
                    ['title' => 'Loaded Cheese Fries', 'price' => 750, 'description' => 'Fires topped with liquid cheese and jalapeños.'],
                    ['title' => 'Animal Style Fries', 'price' => 850, 'description' => 'Fries with grilled onions, cheese, and special sauce.'],
                    ['title' => 'Onion Rings', 'price' => 500, 'description' => 'Beer-battered jumbo onion rings.'],
                    ['title' => 'Mozzarella Sticks', 'price' => 650, 'description' => 'Gooey cheese sticks with marinara.'],
                    ['title' => 'Chicken Nuggets', 'price' => 600, 'description' => '6 pieces of crispy chicken nuggets.'],
                    ['title' => 'Coleslaw', 'price' => 300, 'description' => 'Creamy homemade cabbage salad.'],
                    ['title' => 'Garlic Bread', 'price' => 400, 'description' => 'Toasted baguette with garlic butter.'],
                ]
            ],
            'Beverages' => [
                'image' => 'https://images.unsplash.com/photo-1544145945-f904253db0ad?auto=format&fit=crop&w=800&q=80',
                'items' => [
                    ['title' => 'Classic Cola', 'price' => 250, 'description' => 'Refreshing 330ml can.'],
                    ['title' => 'Fresh Lime Juice', 'price' => 350, 'description' => 'Zesty and chilled.'],
                    ['title' => 'Vanilla Milkshake', 'price' => 650, 'description' => 'Thick and creamy with real vanilla.'],
                    ['title' => 'Chocolate Milkshake', 'price' => 650, 'description' => 'Rich Belgian chocolate blend.'],
                    ['title' => 'Strawberry Milkshake', 'price' => 650, 'description' => 'Made with fresh strawberry syrup.'],
                    ['title' => 'Iced Coffee', 'price' => 550, 'description' => 'Strong brew over ice with cream.'],
                    ['title' => 'Sparkling Water', 'price' => 300, 'description' => '500ml bottle.'],
                    ['title' => 'Orange Fanta', 'price' => 250, 'description' => 'Fruit flavored soda.'],
                    ['title' => 'Mixed Berry Smoothie', 'price' => 750, 'description' => 'Healthy blend of forest berries.'],
                    ['title' => 'Hot Chocolate', 'price' => 500, 'description' => 'Warm and comforting.'],
                ]
            ],
            'Desserts' => [
                'image' => 'https://images.unsplash.com/photo-1551024601-bec78aea704b?auto=format&fit=crop&w=800&q=80',
                'items' => [
                    ['title' => 'Chocolate Lava Cake', 'price' => 850, 'description' => 'Warm cake with a molten center.'],
                    ['title' => 'New York Cheesecake', 'price' => 750, 'description' => 'Creamy cheesecake with berry topping.'],
                    ['title' => 'Apple Pie', 'price' => 650, 'description' => 'Traditional pie with cinnamon apples.'],
                    ['title' => 'Brownie Sundae', 'price' => 900, 'description' => 'Warm brownie with vanilla ice cream.'],
                    ['title' => 'Ice Cream Scoop', 'price' => 300, 'description' => 'Choice of Vanilla, Chocolate, or Strawberry.'],
                    ['title' => 'Fruit Salad', 'price' => 550, 'description' => 'Assorted seasonal fresh fruits.'],
                    ['title' => 'Tiramisu', 'price' => 850, 'description' => 'Coffee-flavored Italian dessert.'],
                    ['title' => 'Red Velvet Cupcake', 'price' => 450, 'description' => 'Soft cupcake with cream cheese frosting.'],
                    ['title' => 'Donut Box (2 pcs)', 'price' => 500, 'description' => 'Glazed or chocolate filled doughnuts.'],
                    ['title' => 'Waffles with Syrup', 'price' => 700, 'description' => 'Crispy waffles with maple syrup and butter.'],
                ]
            ],
        ];

        $order = 1;
        foreach ($categories as $catName => $data) {
            $category = MenuCategory::create([
                'name' => $catName,
                'display_order' => $order++,
                'image_url' => $data['image'],
            ]);

            foreach ($data['items'] as $itemOrder => $itemData) {
                MenuItem::create([
                    'category_id' => $category->id,
                    'title' => $itemData['title'],
                    'price' => $itemData['price'],
                    'description' => $itemData['description'],
                    'image_url' => 'https://picsum.photos/seed/' . rand(1, 1000) . '/800/600',
                    'is_available' => true,
                    'is_popular' => $itemOrder < 3,
                    'display_order' => $itemOrder + 1,
                ]);
            }
        }
    }
}
