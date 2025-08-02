import { Head } from '@inertiajs/react';
import PublicLayout from '@/layouts/public-layout';
import { Users, Target, Heart, Shield, TrendingUp, Award } from 'lucide-react';

export default function About() {
    return (
        <>
            <Head title="About Us - StudList" />
            
            <PublicLayout>
                {/* Hero Section */}
                <div className="relative isolate overflow-hidden bg-gradient-to-b from-cyan-100/20 pt-14">
                    {/* Background Image */}
                    <img
                        src="https://images.unsplash.com/photo-1605895566832-d0cd4c7054a3?w=1600&q=80"
                        alt="Australian cattle farm"
                        className="absolute inset-0 -z-10 h-full w-full object-cover opacity-20"
                    />
                    <div className="absolute inset-0 -z-10 bg-gradient-to-b from-white/80 via-white/50 to-white/80" />
                    
                    <div className="mx-auto max-w-7xl px-6 py-24 sm:py-32 lg:px-8">
                        <div className="mx-auto max-w-2xl lg:mx-0 lg:max-w-xl">
                            <h1 className="text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl">
                                Connecting Australia's Cattle Industry
                            </h1>
                            <p className="mt-6 text-lg leading-8 text-gray-600">
                                StudList is Australia's premier online marketplace for cattle, genetics, and show equipment. 
                                We're building the future of agricultural commerce, one listing at a time.
                            </p>
                        </div>
                        <div className="mx-auto mt-16 grid max-w-2xl grid-cols-1 gap-6 sm:mt-20 lg:mx-0 lg:max-w-none lg:grid-cols-3 lg:gap-8">
                            <div className="flex gap-x-4 rounded-xl bg-white/5 p-6 ring-1 ring-inset ring-gray-200">
                                <Users className="h-7 w-5 flex-none text-cyan-600" />
                                <div className="text-sm leading-6">
                                    <h3 className="font-semibold text-gray-900">Trusted Community</h3>
                                    <p className="mt-2 text-gray-600">Join thousands of farmers and breeders across Australia</p>
                                </div>
                            </div>
                            <div className="flex gap-x-4 rounded-xl bg-white/5 p-6 ring-1 ring-inset ring-gray-200">
                                <Shield className="h-7 w-5 flex-none text-cyan-600" />
                                <div className="text-sm leading-6">
                                    <h3 className="font-semibold text-gray-900">Secure Platform</h3>
                                    <p className="mt-2 text-gray-600">Protected by Stripe's world-class payment security</p>
                                </div>
                            </div>
                            <div className="flex gap-x-4 rounded-xl bg-white/5 p-6 ring-1 ring-inset ring-gray-200">
                                <TrendingUp className="h-7 w-5 flex-none text-cyan-600" />
                                <div className="text-sm leading-6">
                                    <h3 className="font-semibold text-gray-900">Growing Marketplace</h3>
                                    <p className="mt-2 text-gray-600">New listings added daily from across the country</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Our Story Section */}
                <div className="mx-auto max-w-7xl px-6 py-24 sm:py-32 lg:px-8">
                    <h2 className="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl text-center mb-12">
                        Our Story
                    </h2>
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                        <div className="prose prose-lg text-gray-600">
                            <p>
                                StudList was born from a simple observation: the cattle industry needed a better way to connect 
                                buyers and sellers. Traditional methods - word of mouth, local newspapers, and scattered online 
                                forums - weren't cutting it in today's digital age.
                            </p>
                            <p className="mt-6">
                                Founded by farmers who understood the challenges firsthand, StudList set out to create a 
                                centralised platform where quality livestock, genetics, and equipment could be showcased to 
                                serious buyers across Australia. No more missed opportunities, no more limited reach.
                            </p>
                            <p className="mt-6">
                                Today, StudList serves as the trusted marketplace for Australia's cattle industry, facilitating 
                                connections that drive the agricultural economy forward. From prize-winning studs to quality 
                                steers, from cutting-edge genetics to professional show equipment, we're proud to be the 
                                platform where deals get done.
                            </p>
                        </div>
                        <div className="grid grid-cols-2 gap-4">
                            <img
                                src="https://images.unsplash.com/photo-1529528070131-eda9f3e90919?w=600&q=80"
                                alt="Cattle auction"
                                className="rounded-lg shadow-lg w-full h-48 object-cover"
                            />
                            <img
                                src="https://images.unsplash.com/photo-1560493676-04071c5f467b?w=600&q=80"
                                alt="Farmer with cattle"
                                className="rounded-lg shadow-lg w-full h-48 object-cover"
                            />
                            <img
                                src="https://images.unsplash.com/photo-1593030668930-8130abedd2b0?w=600&q=80"
                                alt="Australian farmland"
                                className="rounded-lg shadow-lg w-full h-48 object-cover"
                            />
                            <img
                                src="https://images.unsplash.com/photo-1546869808-8db0d1733e82?w=600&q=80"
                                alt="Cattle show"
                                className="rounded-lg shadow-lg w-full h-48 object-cover"
                            />
                        </div>
                    </div>
                </div>

                {/* Values Section */}
                <div className="relative bg-gray-50 py-24 sm:py-32">
                    {/* Background Image */}
                    <img
                        src="https://images.unsplash.com/photo-1523301551780-cd17359a95d0?w=1600&q=80"
                        alt="Australian ranch landscape"
                        className="absolute inset-0 -z-10 h-full w-full object-cover opacity-5"
                    />
                    <div className="mx-auto max-w-7xl px-6 lg:px-8">
                        <div className="mx-auto max-w-2xl text-center">
                            <h2 className="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                                What We Stand For
                            </h2>
                            <p className="mt-4 text-lg text-gray-600">
                                Our values guide everything we do at StudList
                            </p>
                        </div>
                        <div className="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-none">
                            <dl className="grid max-w-xl grid-cols-1 gap-x-8 gap-y-16 lg:max-w-none lg:grid-cols-4">
                                <div className="flex flex-col">
                                    <dt className="text-base font-semibold leading-7 text-gray-900">
                                        <div className="mb-6 flex h-10 w-10 items-center justify-center rounded-lg bg-cyan-600">
                                            <Target className="h-6 w-6 text-white" />
                                        </div>
                                        Transparency
                                    </dt>
                                    <dd className="mt-1 flex flex-auto flex-col text-base leading-7 text-gray-600">
                                        <p className="flex-auto">
                                            Clear pricing, honest listings, and open communication between buyers and sellers.
                                        </p>
                                    </dd>
                                </div>
                                <div className="flex flex-col">
                                    <dt className="text-base font-semibold leading-7 text-gray-900">
                                        <div className="mb-6 flex h-10 w-10 items-center justify-center rounded-lg bg-cyan-600">
                                            <Shield className="h-6 w-6 text-white" />
                                        </div>
                                        Trust
                                    </dt>
                                    <dd className="mt-1 flex flex-auto flex-col text-base leading-7 text-gray-600">
                                        <p className="flex-auto">
                                            Building a community where handshake deals still matter, backed by modern security.
                                        </p>
                                    </dd>
                                </div>
                                <div className="flex flex-col">
                                    <dt className="text-base font-semibold leading-7 text-gray-900">
                                        <div className="mb-6 flex h-10 w-10 items-center justify-center rounded-lg bg-cyan-600">
                                            <TrendingUp className="h-6 w-6 text-white" />
                                        </div>
                                        Innovation
                                    </dt>
                                    <dd className="mt-1 flex flex-auto flex-col text-base leading-7 text-gray-600">
                                        <p className="flex-auto">
                                            Continuously improving our platform to serve the evolving needs of modern farmers.
                                        </p>
                                    </dd>
                                </div>
                                <div className="flex flex-col">
                                    <dt className="text-base font-semibold leading-7 text-gray-900">
                                        <div className="mb-6 flex h-10 w-10 items-center justify-center rounded-lg bg-cyan-600">
                                            <Heart className="h-6 w-6 text-white" />
                                        </div>
                                        Community
                                    </dt>
                                    <dd className="mt-1 flex flex-auto flex-col text-base leading-7 text-gray-600">
                                        <p className="flex-auto">
                                            Supporting Australian agriculture by connecting people who share a passion for cattle.
                                        </p>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                {/* Stats Section */}
                <div className="relative bg-white py-24 sm:py-32">
                    {/* Background Image */}
                    <img
                        src="https://images.unsplash.com/photo-1569163139394-de4798907684?w=1600&q=80"
                        alt="Busy cattle yard"
                        className="absolute inset-0 -z-10 h-full w-full object-cover opacity-10"
                    />
                    <div className="mx-auto max-w-7xl px-6 lg:px-8">
                        <div className="mx-auto max-w-2xl lg:max-w-none">
                            <div className="text-center">
                                <h2 className="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                                    StudList by the Numbers
                                </h2>
                                <p className="mt-4 text-lg leading-8 text-gray-600">
                                    Growing stronger every day
                                </p>
                            </div>
                            <dl className="mt-16 grid grid-cols-1 gap-0.5 overflow-hidden rounded-2xl text-center sm:grid-cols-2 lg:grid-cols-4">
                                <div className="flex flex-col bg-gray-50 p-8">
                                    <dt className="text-sm font-semibold leading-6 text-gray-600">Active Listings</dt>
                                    <dd className="order-first text-3xl font-semibold tracking-tight text-gray-900">500+</dd>
                                </div>
                                <div className="flex flex-col bg-gray-50 p-8">
                                    <dt className="text-sm font-semibold leading-6 text-gray-600">Registered Users</dt>
                                    <dd className="order-first text-3xl font-semibold tracking-tight text-gray-900">2,000+</dd>
                                </div>
                                <div className="flex flex-col bg-gray-50 p-8">
                                    <dt className="text-sm font-semibold leading-6 text-gray-600">States Covered</dt>
                                    <dd className="order-first text-3xl font-semibold tracking-tight text-gray-900">All 7</dd>
                                </div>
                                <div className="flex flex-col bg-gray-50 p-8">
                                    <dt className="text-sm font-semibold leading-6 text-gray-600">Success Rate</dt>
                                    <dd className="order-first text-3xl font-semibold tracking-tight text-gray-900">95%</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                {/* Image Showcase Section */}
                <div className="bg-gray-50 py-24 sm:py-32">
                    <div className="mx-auto max-w-7xl px-6 lg:px-8">
                        <div className="mx-auto max-w-2xl text-center mb-12">
                            <h2 className="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                                Life on StudList
                            </h2>
                            <p className="mt-4 text-lg text-gray-600">
                                From paddock to sale yard, we're part of your journey
                            </p>
                        </div>
                        <div className="grid grid-cols-2 md:grid-cols-3 gap-4">
                            <div className="aspect-w-3 aspect-h-2">
                                <img
                                    src="https://images.unsplash.com/photo-1516467508483-a7212febe31a?w=600&q=80"
                                    alt="Angus cattle"
                                    className="rounded-lg object-cover w-full h-48"
                                />
                            </div>
                            <div className="aspect-w-3 aspect-h-2">
                                <img
                                    src="https://images.unsplash.com/photo-1589923158776-cb4485d99fd6?w=600&q=80"
                                    alt="Farm equipment"
                                    className="rounded-lg object-cover w-full h-48"
                                />
                            </div>
                            <div className="aspect-w-3 aspect-h-2">
                                <img
                                    src="https://images.unsplash.com/photo-1548550023-2bdb3c5beed7?w=600&q=80"
                                    alt="Genetics laboratory"
                                    className="rounded-lg object-cover w-full h-48"
                                />
                            </div>
                            <div className="aspect-w-3 aspect-h-2">
                                <img
                                    src="https://images.unsplash.com/photo-1606041011872-596597976b25?w=600&q=80"
                                    alt="Show cattle preparation"
                                    className="rounded-lg object-cover w-full h-48"
                                />
                            </div>
                            <div className="aspect-w-3 aspect-h-2">
                                <img
                                    src="https://images.unsplash.com/photo-1591213954196-2d0ccb3f8d4c?w=600&q=80"
                                    alt="Australian farmland"
                                    className="rounded-lg object-cover w-full h-48"
                                />
                            </div>
                            <div className="aspect-w-3 aspect-h-2">
                                <img
                                    src="https://images.unsplash.com/photo-1444858291040-58f756a3bdd6?w=600&q=80"
                                    alt="Farmer at sunset"
                                    className="rounded-lg object-cover w-full h-48"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                {/* Why Choose StudList */}
                <div className="bg-cyan-50 py-24 sm:py-32">
                    <div className="mx-auto max-w-7xl px-6 lg:px-8">
                        <div className="mx-auto max-w-2xl text-center">
                            <h2 className="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                                Why Sellers Choose StudList
                            </h2>
                            <p className="mt-6 text-lg leading-8 text-gray-600">
                                We've built the platform with your success in mind
                            </p>
                        </div>
                        <div className="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-4xl">
                            <dl className="grid max-w-xl grid-cols-1 gap-x-8 gap-y-10 lg:max-w-none lg:grid-cols-2 lg:gap-y-16">
                                <div className="relative pl-16">
                                    <dt className="text-base font-semibold leading-7 text-gray-900">
                                        <div className="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-cyan-600">
                                            <Award className="h-6 w-6 text-white" />
                                        </div>
                                        Simple, Fair Pricing
                                    </dt>
                                    <dd className="mt-2 text-base leading-7 text-gray-600">
                                        Just $15 per month keeps all your listings active. No hidden fees, no commission on sales, 
                                        no complicated tiers. Cancel anytime.
                                    </dd>
                                </div>
                                <div className="relative pl-16">
                                    <dt className="text-base font-semibold leading-7 text-gray-900">
                                        <div className="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-cyan-600">
                                            <Users className="h-6 w-6 text-white" />
                                        </div>
                                        Nationwide Reach
                                    </dt>
                                    <dd className="mt-2 text-base leading-7 text-gray-600">
                                        Your listings are visible to serious buyers across Australia, not just your local area. 
                                        Expand your market exponentially.
                                    </dd>
                                </div>
                                <div className="relative pl-16">
                                    <dt className="text-base font-semibold leading-7 text-gray-900">
                                        <div className="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-cyan-600">
                                            <Shield className="h-6 w-6 text-white" />
                                        </div>
                                        Secure & Professional
                                    </dt>
                                    <dd className="mt-2 text-base leading-7 text-gray-600">
                                        Professional listing pages, secure payment processing through Stripe, and a platform 
                                        that presents your stock in the best light.
                                    </dd>
                                </div>
                                <div className="relative pl-16">
                                    <dt className="text-base font-semibold leading-7 text-gray-900">
                                        <div className="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-cyan-600">
                                            <Heart className="h-6 w-6 text-white" />
                                        </div>
                                        Built by Farmers
                                    </dt>
                                    <dd className="mt-2 text-base leading-7 text-gray-600">
                                        We understand the industry because we're part of it. Every feature is designed with 
                                        real-world cattle trading in mind.
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </PublicLayout>
        </>
    );
}