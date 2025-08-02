import { type SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';
import { ArrowRight, CheckCircle, DollarSign, FileText, Menu, Users, X, Truck, Heart, Shield, Star } from 'lucide-react';
import { useState } from 'react';

interface Listing {
    id: number;
    name: string;
    photos?: string[];
    price?: number;
    breed?: string;
    location?: string;
    business_contact?: string;
    status: string;
    user: {
        name: string;
    };
}

interface WelcomeProps {
    steerListings: Listing[];
    studListings: Listing[];
    geneticsListings: Listing[];
    showEquipmentListings: Listing[];
}

export default function Welcome({ steerListings, studListings, geneticsListings, showEquipmentListings }: WelcomeProps) {
    const { auth } = usePage<SharedData>().props;
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

    const allListings = [
        ...steerListings.map(l => ({ ...l, type: 'Steer' })),
        ...studListings.map(l => ({ ...l, type: 'Stud' })),
        ...geneticsListings.map(l => ({ ...l, type: 'Genetics' })),
        ...showEquipmentListings.map(l => ({ ...l, type: 'Equipment' })),
    ];

    const categories = [
        {
            name: 'Steers',
            description: 'Premium cattle ready for your operation',
            image: 'https://images.unsplash.com/photo-1527153857715-3908f2bae5e8?w=800&q=80',
            count: steerListings.length,
            color: 'bg-amber-600',
        },
        {
            name: 'Studs',
            description: 'Elite breeding bulls with proven genetics',
            image: 'https://images.unsplash.com/photo-1569880153113-76e33fd8d146?w=800&q=80',
            count: studListings.length,
            color: 'bg-red-600',
        },
        {
            name: 'Genetics',
            description: 'High-quality semen straws and embryos',
            image: 'https://images.unsplash.com/photo-1548550023-2bdb3c5beed7?w=800&q=80',
            count: geneticsListings.length,
            color: 'bg-purple-600',
        },
        {
            name: 'Equipment',
            description: 'Professional show and farm equipment',
            image: 'https://images.unsplash.com/photo-1625246333195-78d9c38ad449?w=800&q=80',
            count: showEquipmentListings.length,
            color: 'bg-green-600',
        },
    ];

    return (
        <>
            <Head title="Australia's Premier Cattle Marketplace" />
            
            <div className="min-h-screen bg-white">
                {/* Header */}
                <header className="absolute inset-x-0 top-0 z-50 bg-white/95 backdrop-blur-sm">
                    <nav className="mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8" aria-label="Global">
                        <div className="flex lg:flex-1">
                            <Link href="/" className="-m-1.5 p-1.5">
                                <span className="text-2xl font-bold text-gray-900">StudList</span>
                            </Link>
                        </div>
                        <div className="flex lg:hidden">
                            <button
                                type="button"
                                className="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700"
                                onClick={() => setMobileMenuOpen(true)}
                            >
                                <span className="sr-only">Open main menu</span>
                                <Menu className="h-6 w-6" />
                            </button>
                        </div>
                        <div className="hidden lg:flex lg:gap-x-12">
                            <Link href={route('search')} className="text-sm font-semibold leading-6 text-gray-900">Search Listings</Link>
                            <a href="#categories" className="text-sm font-semibold leading-6 text-gray-900">Categories</a>
                            <a href="#features" className="text-sm font-semibold leading-6 text-gray-900">Features</a>
                            <a href="#how-it-works" className="text-sm font-semibold leading-6 text-gray-900">How It Works</a>
                        </div>
                        <div className="hidden lg:flex lg:flex-1 lg:justify-end">
                            <div className="flex items-center gap-x-4">
                                {auth.user ? (
                                    <Link href={route('dashboard')} className="text-sm font-semibold leading-6 text-gray-900">
                                        Dashboard <span aria-hidden="true">&rarr;</span>
                                    </Link>
                                ) : (
                                    <>
                                        <Link href={route('login')} className="text-sm font-semibold leading-6 text-gray-900 whitespace-nowrap">
                                            Log in
                                        </Link>
                                        <Link href={route('register')} className="rounded-md bg-cyan-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-cyan-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-cyan-600 whitespace-nowrap">
                                            Start Listing
                                        </Link>
                                    </>
                                )}
                            </div>
                        </div>
                    </nav>
                    {/* Mobile menu */}
                    <div className={`lg:hidden ${mobileMenuOpen ? '' : 'hidden'}`}>
                        <div className="fixed inset-0 z-50" />
                        <div className="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
                            <div className="flex items-center justify-between">
                                <Link href="/" className="-m-1.5 p-1.5">
                                    <span className="text-2xl font-bold text-gray-900">StudList</span>
                                </Link>
                                <button
                                    type="button"
                                    className="-m-2.5 rounded-md p-2.5 text-gray-700"
                                    onClick={() => setMobileMenuOpen(false)}
                                >
                                    <span className="sr-only">Close menu</span>
                                    <X className="h-6 w-6" />
                                </button>
                            </div>
                            <div className="mt-6 flow-root">
                                <div className="-my-6 divide-y divide-gray-500/10">
                                    <div className="space-y-2 py-6">
                                        <Link href={route('search')} className="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">Search Listings</Link>
                                        <a href="#categories" className="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">Categories</a>
                                        <a href="#features" className="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">Features</a>
                                        <a href="#how-it-works" className="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">How It Works</a>
                                    </div>
                                    <div className="py-6">
                                        {auth.user ? (
                                            <Link href={route('dashboard')} className="-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">
                                                Dashboard
                                            </Link>
                                        ) : (
                                            <>
                                                <Link href={route('login')} className="-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">
                                                    Log in
                                                </Link>
                                                <Link href={route('register')} className="-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">
                                                    Register
                                                </Link>
                                            </>
                                        )}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                {/* Hero Section with Background Image */}
                <div className="relative isolate overflow-hidden">
                    <img
                        src="https://images.unsplash.com/photo-1500595046743-cd271d694d30?w=1600&q=80"
                        alt="Cattle grazing"
                        className="absolute inset-0 -z-10 h-full w-full object-cover"
                    />
                    <div className="absolute inset-0 -z-10 bg-gradient-to-b from-black/60 via-black/40 to-black/60" />
                    <div className="mx-auto max-w-2xl py-32 sm:py-48 lg:py-56">
                        <div className="text-center">
                            <h1 className="text-4xl font-bold tracking-tight text-white sm:text-6xl drop-shadow-lg">
                                Australia's Premier Cattle Marketplace
                            </h1>
                            <p className="mt-6 text-lg leading-8 text-gray-100 drop-shadow">
                                Connect with buyers and sellers nationwide. List your steers, studs, genetics, and equipment with Australia's most trusted cattle trading platform.
                            </p>
                            <div className="mt-10 flex items-center justify-center gap-x-6">
                                <Link href={route('register')} className="rounded-md bg-cyan-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-cyan-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-cyan-600">
                                    Start Listing Today
                                </Link>
                                <a href="#categories" className="text-sm font-semibold leading-6 text-white">
                                    Browse Categories <span aria-hidden="true">→</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Categories Section with Large Photos */}
                <div id="categories" className="py-24 sm:py-32 bg-gray-50">
                    <div className="mx-auto max-w-7xl px-6 lg:px-8">
                        <div className="mx-auto max-w-2xl text-center">
                            <h2 className="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Browse by Category</h2>
                            <p className="mt-4 text-lg text-gray-600">Find exactly what you're looking for in our marketplace</p>
                        </div>
                        <div className="mx-auto mt-16 grid max-w-2xl grid-cols-1 gap-6 sm:mt-20 lg:mx-0 lg:max-w-none lg:grid-cols-4">
                            {categories.map((category) => (
                                <div key={category.name} className="relative isolate overflow-hidden rounded-2xl bg-gray-900 px-6 pb-6 pt-48 sm:pt-40 lg:pt-48">
                                    <img
                                        src={category.image}
                                        alt={category.name}
                                        className="absolute inset-0 -z-10 h-full w-full object-cover"
                                    />
                                    <div className="absolute inset-0 -z-10 bg-gradient-to-t from-gray-900 via-gray-900/40" />
                                    <div className="absolute inset-0 -z-10 rounded-2xl ring-1 ring-inset ring-gray-900/10" />
                                    <div className="flex flex-wrap items-center gap-y-1 overflow-hidden text-sm leading-6 text-gray-300">
                                        <span className={`mr-8 inline-flex items-center gap-x-2 rounded-full px-3 py-1 text-xs font-medium text-white ${category.color}`}>
                                            {category.count} Active Listings
                                        </span>
                                    </div>
                                    <h3 className="mt-3 text-lg font-semibold leading-6 text-white">
                                        <Link href={route('search', { category: [category.name.toLowerCase()] })}>
                                            <span className="absolute inset-0" />
                                            {category.name}
                                        </Link>
                                    </h3>
                                    <p className="mt-2 text-sm text-gray-300">{category.description}</p>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>


                {/* Trust Indicators with Photos */}
                <div className="bg-cyan-50 py-24 sm:py-32">
                    <div className="mx-auto max-w-7xl px-6 lg:px-8">
                        <div className="mx-auto grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 lg:max-w-none lg:grid-cols-2">
                            <div className="max-w-xl lg:max-w-lg">
                                <h2 className="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Why sellers trust StudList</h2>
                                <p className="mt-6 text-lg leading-8 text-gray-600">
                                    Join thousands of Australian farmers and breeders who use StudList to connect with serious buyers.
                                </p>
                                <dl className="mt-10 max-w-xl space-y-8 text-base leading-7 text-gray-600 lg:max-w-none">
                                    <div className="relative pl-9">
                                        <dt className="inline font-semibold text-gray-900">
                                            <Shield className="absolute left-1 top-1 h-5 w-5 text-cyan-600" />
                                            Secure Payments
                                        </dt>
                                        <dd className="inline"> All transactions are protected by Stripe's world-class security.</dd>
                                    </div>
                                    <div className="relative pl-9">
                                        <dt className="inline font-semibold text-gray-900">
                                            <Users className="absolute left-1 top-1 h-5 w-5 text-cyan-600" />
                                            Verified Buyers
                                        </dt>
                                        <dd className="inline"> Connect with a network of serious, verified cattle buyers.</dd>
                                    </div>
                                    <div className="relative pl-9">
                                        <dt className="inline font-semibold text-gray-900">
                                            <Star className="absolute left-1 top-1 h-5 w-5 text-cyan-600" />
                                            Quality Listings
                                        </dt>
                                        <dd className="inline"> Our platform showcases only the best cattle and equipment.</dd>
                                    </div>
                                </dl>
                            </div>
                            <div className="grid grid-cols-2 grid-rows-2 gap-4 sm:gap-6 lg:gap-8">
                                <img
                                    src="https://images.unsplash.com/photo-1570042225831-d5b3e8b2b7b3?w=600&q=80"
                                    alt="Cattle auction"
                                    className="rounded-lg bg-gray-100 object-cover h-full w-full"
                                />
                                <img
                                    src="https://images.unsplash.com/photo-1625766763788-95dcce9bf5ac?w=600&q=80"
                                    alt="Farm equipment"
                                    className="rounded-lg bg-gray-100 object-cover h-full w-full"
                                />
                                <img
                                    src="https://images.unsplash.com/photo-1523473827533-2a64d0d36748?w=600&q=80"
                                    alt="Cattle show"
                                    className="rounded-lg bg-gray-100 object-cover h-full w-full"
                                />
                                <img
                                    src="https://images.unsplash.com/photo-1516467508483-a7212febe31a?w=600&q=80"
                                    alt="Cattle farming"
                                    className="rounded-lg bg-gray-100 object-cover h-full w-full"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                {/* Features Section */}
                <div id="features" className="py-24 sm:py-32">
                    <div className="mx-auto max-w-7xl px-6 lg:px-8">
                        <div className="mx-auto max-w-2xl lg:text-center">
                            <h2 className="text-base font-semibold leading-7 text-cyan-600">Everything you need</h2>
                            <p className="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                                Built for the cattle industry
                            </p>
                        </div>
                        <div className="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-none">
                            <dl className="grid max-w-xl grid-cols-1 gap-x-8 gap-y-16 lg:max-w-none lg:grid-cols-3">
                                <div className="flex flex-col">
                                    <dt className="flex items-center gap-x-3 text-base font-semibold leading-7 text-gray-900">
                                        <FileText className="h-5 w-5 flex-none text-cyan-600" />
                                        Easy Listing Creation
                                    </dt>
                                    <dd className="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600">
                                        <p className="flex-auto">Create detailed listings for your cattle, genetics, and equipment in minutes with our intuitive interface.</p>
                                    </dd>
                                </div>
                                <div className="flex flex-col">
                                    <dt className="flex items-center gap-x-3 text-base font-semibold leading-7 text-gray-900">
                                        <DollarSign className="h-5 w-5 flex-none text-cyan-600" />
                                        Simple Pricing
                                    </dt>
                                    <dd className="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600">
                                        <p className="flex-auto">Just $15/month keeps all your listings active. Cancel anytime through our secure Stripe integration.</p>
                                    </dd>
                                </div>
                                <div className="flex flex-col">
                                    <dt className="flex items-center gap-x-3 text-base font-semibold leading-7 text-gray-900">
                                        <Truck className="h-5 w-5 flex-none text-cyan-600" />
                                        Nationwide Reach
                                    </dt>
                                    <dd className="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600">
                                        <p className="flex-auto">Connect with buyers and sellers across Australia, from local farms to major operations.</p>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                {/* How It Works Section with Visual Steps */}
                <div id="how-it-works" className="py-24 sm:py-32 bg-gray-50">
                    <div className="mx-auto max-w-7xl px-6 lg:px-8">
                        <div className="mx-auto max-w-2xl lg:text-center">
                            <h2 className="text-base font-semibold leading-7 text-cyan-600">Simple Process</h2>
                            <p className="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                                Start selling in 3 easy steps
                            </p>
                        </div>
                        <div className="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-none">
                            <div className="grid grid-cols-1 gap-y-16 lg:grid-cols-3 lg:gap-x-12 lg:gap-y-0">
                                <div className="relative">
                                    <div className="flex items-center justify-center">
                                        <img
                                            src="https://images.unsplash.com/photo-1603048588665-791ca8aea617?w=400&q=80"
                                            alt="Create listing"
                                            className="h-48 w-48 rounded-full object-cover"
                                        />
                                    </div>
                                    <div className="mt-6 text-center">
                                        <div className="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-cyan-600 text-white">
                                            <span className="text-lg font-bold">1</span>
                                        </div>
                                        <h3 className="mt-4 text-lg font-semibold leading-8 text-gray-900">Create Your Listing</h3>
                                        <p className="mt-2 text-base leading-7 text-gray-600">
                                            Upload photos and details about your cattle or equipment
                                        </p>
                                    </div>
                                </div>
                                <div className="relative">
                                    <div className="flex items-center justify-center">
                                        <img
                                            src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=400&q=80"
                                            alt="Subscribe"
                                            className="h-48 w-48 rounded-full object-cover"
                                        />
                                    </div>
                                    <div className="mt-6 text-center">
                                        <div className="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-cyan-600 text-white">
                                            <span className="text-lg font-bold">2</span>
                                        </div>
                                        <h3 className="mt-4 text-lg font-semibold leading-8 text-gray-900">Subscribe for $15/month</h3>
                                        <p className="mt-2 text-base leading-7 text-gray-600">
                                            Activate your listings with our simple monthly subscription
                                        </p>
                                    </div>
                                </div>
                                <div className="relative">
                                    <div className="flex items-center justify-center">
                                        <img
                                            src="https://images.unsplash.com/photo-1605000797499-95a51c5269ae?w=400&q=80"
                                            alt="Connect"
                                            className="h-48 w-48 rounded-full object-cover"
                                        />
                                    </div>
                                    <div className="mt-6 text-center">
                                        <div className="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-cyan-600 text-white">
                                            <span className="text-lg font-bold">3</span>
                                        </div>
                                        <h3 className="mt-4 text-lg font-semibold leading-8 text-gray-900">Connect with Buyers</h3>
                                        <p className="mt-2 text-base leading-7 text-gray-600">
                                            Start receiving inquiries from interested buyers immediately
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Testimonials Section */}
                <div className="bg-white py-24 sm:py-32">
                    <div className="mx-auto max-w-7xl px-6 lg:px-8">
                        <div className="mx-auto max-w-xl text-center">
                            <h2 className="text-lg font-semibold leading-8 tracking-tight text-cyan-600">Testimonials</h2>
                            <p className="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                                Trusted by farmers across Australia
                            </p>
                        </div>
                        <div className="mx-auto mt-16 flow-root max-w-2xl sm:mt-20 lg:mx-0 lg:max-w-none">
                            <div className="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                                <div className="pt-8 sm:inline-block sm:w-full sm:px-4">
                                    <figure className="rounded-2xl bg-gray-50 p-8 text-sm leading-6">
                                        <blockquote className="text-gray-900">
                                            <p>"StudList made selling my prize steer incredibly easy. Had multiple interested buyers within days!"</p>
                                        </blockquote>
                                        <figcaption className="mt-6 flex items-center gap-x-4">
                                            <img
                                                className="h-10 w-10 rounded-full bg-gray-50"
                                                src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&q=80"
                                                alt=""
                                            />
                                            <div>
                                                <div className="font-semibold text-gray-900">John Smith</div>
                                                <div className="text-gray-600">Cattle Breeder, NSW</div>
                                            </div>
                                        </figcaption>
                                    </figure>
                                </div>
                                <div className="pt-8 sm:inline-block sm:w-full sm:px-4">
                                    <figure className="rounded-2xl bg-gray-50 p-8 text-sm leading-6">
                                        <blockquote className="text-gray-900">
                                            <p>"The platform is so professional. Love being able to showcase my genetics with detailed photos and info."</p>
                                        </blockquote>
                                        <figcaption className="mt-6 flex items-center gap-x-4">
                                            <img
                                                className="h-10 w-10 rounded-full bg-gray-50"
                                                src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100&q=80"
                                                alt=""
                                            />
                                            <div>
                                                <div className="font-semibold text-gray-900">Sarah Johnson</div>
                                                <div className="text-gray-600">Stud Owner, QLD</div>
                                            </div>
                                        </figcaption>
                                    </figure>
                                </div>
                                <div className="pt-8 sm:inline-block sm:w-full sm:px-4">
                                    <figure className="rounded-2xl bg-gray-50 p-8 text-sm leading-6">
                                        <blockquote className="text-gray-900">
                                            <p>"Best investment for my farm business. The monthly fee is nothing compared to the exposure we get."</p>
                                        </blockquote>
                                        <figcaption className="mt-6 flex items-center gap-x-4">
                                            <img
                                                className="h-10 w-10 rounded-full bg-gray-50"
                                                src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100&q=80"
                                                alt=""
                                            />
                                            <div>
                                                <div className="font-semibold text-gray-900">Mike Wilson</div>
                                                <div className="text-gray-600">Equipment Dealer, VIC</div>
                                            </div>
                                        </figcaption>
                                    </figure>
                                </div>
                                <div className="pt-8 sm:inline-block sm:w-full sm:px-4">
                                    <figure className="rounded-2xl bg-gray-50 p-8 text-sm leading-6">
                                        <blockquote className="text-gray-900">
                                            <p>"Sold my entire yearling draft through StudList. The reach is incredible - buyers from all over Australia!"</p>
                                        </blockquote>
                                        <figcaption className="mt-6 flex items-center gap-x-4">
                                            <img
                                                className="h-10 w-10 rounded-full bg-gray-50"
                                                src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100&q=80"
                                                alt=""
                                            />
                                            <div>
                                                <div className="font-semibold text-gray-900">David Thompson</div>
                                                <div className="text-gray-600">Angus Breeder, SA</div>
                                            </div>
                                        </figcaption>
                                    </figure>
                                </div>
                                <div className="pt-8 sm:inline-block sm:w-full sm:px-4">
                                    <figure className="rounded-2xl bg-gray-50 p-8 text-sm leading-6">
                                        <blockquote className="text-gray-900">
                                            <p>"The genetics marketplace is fantastic. I've sourced top quality semen from breeders I'd never have found otherwise."</p>
                                        </blockquote>
                                        <figcaption className="mt-6 flex items-center gap-x-4">
                                            <img
                                                className="h-10 w-10 rounded-full bg-gray-50"
                                                src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&q=80"
                                                alt=""
                                            />
                                            <div>
                                                <div className="font-semibold text-gray-900">Emma Roberts</div>
                                                <div className="text-gray-600">Dairy Farmer, TAS</div>
                                            </div>
                                        </figcaption>
                                    </figure>
                                </div>
                                <div className="pt-8 sm:inline-block sm:w-full sm:px-4">
                                    <figure className="rounded-2xl bg-gray-50 p-8 text-sm leading-6">
                                        <blockquote className="text-gray-900">
                                            <p>"As a show equipment supplier, StudList connects me directly with serious competitors. Worth every cent!"</p>
                                        </blockquote>
                                        <figcaption className="mt-6 flex items-center gap-x-4">
                                            <img
                                                className="h-10 w-10 rounded-full bg-gray-50"
                                                src="https://images.unsplash.com/photo-1519345182560-3f2917c472ef?w=100&q=80"
                                                alt=""
                                            />
                                            <div>
                                                <div className="font-semibold text-gray-900">Peter Chen</div>
                                                <div className="text-gray-600">Equipment Supplier, WA</div>
                                            </div>
                                        </figcaption>
                                    </figure>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* CTA Section with Background Image */}
                <div id="pricing" className="relative isolate overflow-hidden bg-gray-900">
                    <img
                        src="https://images.unsplash.com/photo-1530836369250-ef72a3f5cda8?w=1600&q=80"
                        alt="Cattle sunset"
                        className="absolute inset-0 -z-10 h-full w-full object-cover opacity-30"
                    />
                    <div className="px-6 py-24 sm:px-6 sm:py-32 lg:px-8">
                        <div className="mx-auto max-w-2xl text-center">
                            <h2 className="text-3xl font-bold tracking-tight text-white sm:text-4xl">
                                Ready to list your cattle?
                            </h2>
                            <p className="mx-auto mt-6 max-w-xl text-lg leading-8 text-gray-300">
                                Join Australia's fastest-growing cattle marketplace. Only $15/month for unlimited listings.
                            </p>
                            <div className="mt-10 flex items-center justify-center gap-x-6">
                                <Link href={route('register')} className="rounded-md bg-white px-3.5 py-2.5 text-sm font-semibold text-cyan-600 shadow-sm hover:bg-cyan-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                                    Get Started
                                </Link>
                                <Link href={route('login')} className="text-sm font-semibold leading-6 text-white">
                                    Already have an account? Sign in <span aria-hidden="true">→</span>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Footer */}
                <footer className="bg-gray-900">
                    <div className="mx-auto max-w-7xl overflow-hidden px-6 py-20 sm:py-24 lg:px-8">
                        <nav className="-mb-6 columns-2 sm:flex sm:justify-center sm:space-x-12" aria-label="Footer">
                            <div className="pb-6">
                                <Link href={route('about')} className="text-sm leading-6 text-gray-300 hover:text-white">About</Link>
                            </div>
                            <div className="pb-6">
                                <a href="#" className="text-sm leading-6 text-gray-300 hover:text-white">Contact</a>
                            </div>
                            <div className="pb-6">
                                <a href="#" className="text-sm leading-6 text-gray-300 hover:text-white">Terms</a>
                            </div>
                            <div className="pb-6">
                                <a href="#" className="text-sm leading-6 text-gray-300 hover:text-white">Privacy</a>
                            </div>
                        </nav>
                        <p className="mt-10 text-center text-xs leading-5 text-gray-400">
                            &copy; 2024 StudList. All rights reserved.
                        </p>
                    </div>
                </footer>
            </div>
        </>
    );
}