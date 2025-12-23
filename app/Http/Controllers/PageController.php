<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    /**
     * Display the about us page.
     */
    public function about()
    {
        return view('pages.about');
    }

    /**
     * Display the services page.
     */
    public function services()
    {
        return view('pages.services');
    }

    /**
     * Display the contact us page.
     */
    public function contact()
    {
        return view('pages.contact');
    }

    /**
     * Handle contact form submission.
     */
    public function contactSubmit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->route('contact')
                ->withErrors($validator)
                ->withInput();
        }

        ContactMessage::create($request->only(['name', 'email', 'phone', 'subject', 'message']));

        return redirect()->route('contact')
            ->with('success', 'Thank you for contacting us! We will get back to you soon.');
    }

    /**
     * Display special offers page.
     */
    public function offers()
    {
        $discountedProducts = Product::whereNotNull('discount_price')
            ->where('is_active', true)
            ->where('discount_price', '<', \DB::raw('base_price'))
            ->with(['images', 'category'])
            ->orderByRaw('(base_price - discount_price) DESC')
            ->paginate(12);

        $featuredOffers = Product::where('is_featured', true)
            ->where('is_active', true)
            ->with(['images', 'category'])
            ->limit(6)
            ->get();

        return view('pages.offers', compact('discountedProducts', 'featuredOffers'));
    }

    /**
     * Display terms and conditions page.
     */
    public function terms()
    {
        return view('pages.terms');
    }

    /**
     * Display privacy policy page.
     */
    public function privacy()
    {
        return view('pages.privacy');
    }

    /**
     * Display FAQ page.
     */
    public function faq()
    {
        $faqs = [
            [
                'question' => 'What is your return policy?',
                'answer' => 'We offer a 30-day return policy for all products. Items must be in original condition with all accessories and packaging.'
            ],
            [
                'question' => 'How long does shipping take?',
                'answer' => 'Standard shipping takes 3-5 business days. Express shipping is available for next-day delivery in most areas.'
            ],
            [
                'question' => 'Do you offer international shipping?',
                'answer' => 'Yes, we ship to over 50 countries worldwide. Shipping costs and delivery times vary by location.'
            ],
            [
                'question' => 'What payment methods do you accept?',
                'answer' => 'We accept Visa, MasterCard, American Express, PayPal, and bank transfers.'
            ],
            [
                'question' => 'How can I track my order?',
                'answer' => 'Once your order ships, you will receive a tracking number via email that you can use to track your package.'
            ],
            [
                'question' => 'Do you offer warranty on products?',
                'answer' => 'Yes, most products come with a 1-year manufacturer warranty. Some products have extended warranty options.'
            ],
            [
                'question' => 'Can I cancel my order?',
                'answer' => 'You can cancel your order within 24 hours of placing it, provided it hasn\'t been shipped yet.'
            ],
            [
                'question' => 'How do I contact customer support?',
                'answer' => 'You can contact our customer support team via phone, email, or live chat during business hours.'
            ],
        ];

        return view('pages.faq', compact('faqs'));
    }

    /**
     * Display sitemap.
     */
    public function sitemap()
    {
        return view('pages.sitemap');
    }

    /**
     * Display shipping information page.
     */
    public function shipping()
    {
        return view('pages.shipping');
    }

    /**
     * Display returns and refunds page.
     */
    public function returns()
    {
        return view('pages.returns');
    }

    /**
     * Display careers page.
     */
    public function careers()
    {
        return view('pages.careers');
    }

    /**
     * Display blog page.
     */
    public function blog()
    {
        // In a real application, you would fetch blog posts from database
        $blogPosts = [
            [
                'id' => 1,
                'title' => 'Top 10 Gadgets You Need in 2024',
                'excerpt' => 'Discover the must-have electronics and gadgets for this year.',
                'image' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661',
                'date' => 'Jan 15, 2024',
                'author' => 'John Doe',
                'category' => 'Gadgets'
            ],
            [
                'id' => 2,
                'title' => 'How to Choose the Right Smartphone',
                'excerpt' => 'A comprehensive guide to selecting the perfect smartphone for your needs.',
                'image' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9',
                'date' => 'Jan 10, 2024',
                'author' => 'Jane Smith',
                'category' => 'Smartphones'
            ],
        ];

        return view('pages.blog', compact('blogPosts'));
    }

    /**
     * Display single blog post.
     */
    public function blogPost($slug)
    {
        // In a real application, you would fetch blog post by slug
        $post = [
            'title' => 'Top 10 Gadgets You Need in 2024',
            'content' => '<p>Discover the must-have electronics and gadgets for this year...</p>',
            'image' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661',
            'date' => 'Jan 15, 2024',
            'author' => 'John Doe',
            'category' => 'Gadgets'
        ];

        $relatedPosts = [
            [
                'id' => 2,
                'title' => 'How to Choose the Right Smartphone',
                'slug' => 'how-to-choose-right-smartphone'
            ],
        ];

        return view('pages.blog-post', compact('post', 'relatedPosts'));
    }

    /**
     * Display store locator page.
     */
    public function storeLocator()
    {
        $stores = [
            [
                'name' => 'Main Store',
                'address' => '123 Tech Street, San Francisco, CA 94107',
                'phone' => '(415) 123-4567',
                'hours' => 'Mon-Sat: 9AM-9PM, Sun: 10AM-6PM',
                'lat' => 37.7749,
                'lng' => -122.4194
            ],
            [
                'name' => 'Downtown Store',
                'address' => '456 Market Street, San Francisco, CA 94105',
                'phone' => '(415) 987-6543',
                'hours' => 'Mon-Fri: 10AM-8PM, Sat-Sun: 10AM-6PM',
                'lat' => 37.7885,
                'lng' => -122.4082
            ],
        ];

        return view('pages.store-locator', compact('stores'));
    }
}
