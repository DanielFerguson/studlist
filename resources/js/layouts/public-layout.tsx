import { Link, usePage } from '@inertiajs/react';
import { Menu, X } from 'lucide-react';
import { PropsWithChildren, useState } from 'react';
import { type SharedData } from '@/types';

export default function PublicLayout({ children }: PropsWithChildren) {
    const { auth } = usePage<SharedData>().props;
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

    return (
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
                        <a href="/#categories" className="text-sm font-semibold leading-6 text-gray-900">Categories</a>
                        <a href="/#features" className="text-sm font-semibold leading-6 text-gray-900">Features</a>
                        <a href="/#how-it-works" className="text-sm font-semibold leading-6 text-gray-900">How It Works</a>
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
                                    <a href="/#categories" className="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">Categories</a>
                                    <a href="/#features" className="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">Features</a>
                                    <a href="/#how-it-works" className="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">How It Works</a>
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

            {/* Main Content */}
            <main className="pt-20">
                {children}
            </main>

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
    );
}