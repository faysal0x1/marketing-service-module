import React from 'react';
import { Head, useForm } from '@inertiajs/react';
import { Inertia } from '@inertiajs/inertia';
import AppLayout from '@/layouts/app-layout.jsx';
import TextLink from '@/components/text-link.jsx';

const MarketingIndex = ({ services, flash }) => {
    return (
        <AppLayout>
            <Head title="Marketing Services" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {flash?.success && (
                        <div className="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {flash.success}
                        </div>
                    )}

                    {flash?.error && (
                        <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {flash.error}
                        </div>
                    )}

                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 bg-white border-b border-gray-200">
                            <h1 className="text-2xl font-bold mb-6">Marketing Services</h1>

                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                {services.map((service) => (
                                    <div key={service.id} className="border rounded-lg overflow-hidden">
                                        <div className="p-4 border-b bg-gray-50">
                                            <div className="flex justify-between items-center">
                                                <h2 className="text-xl font-semibold">{service.name}</h2>
                                                <div className="flex items-center">
                                                    <div
                                                        className={`w-3 h-3 rounded-full mr-2 ${service.is_active ? 'bg-green-500' : 'bg-red-500'}`}
                                                    ></div>
                                                    <span className="text-sm text-gray-600">{service.is_active ? 'Active' : 'Inactive'}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div className="p-4">
                                            <p className="text-gray-600 mb-4">{service.description}</p>

                                            <div className="mt-4 flex flex-wrap gap-2">

                                                <TextLink href={route('marketing-service.edit', service.slug)}
                                                    className="inline-flex items-center px-4 py-2 bg-blue-600 border
                                                           border-transparent rounded-md font-semibold text-xs text-white uppercase
                                                            tracking-widest hover:bg-blue-700 active:bg-blue-900
                                                             focus:outline-none focus:border-blue-900 focus:shadow-outline-blue
                                                              transition ease-in-out duration-150"
                                                >
                                                    Configure
                                                </TextLink>



                                                <button
                                                    onClick={() => Inertia.post(route('admin.marketing.toggle-status', service.slug))}
                                                    className={`inline-flex items-center px-4 py-2 border rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:shadow-outline-gray transition ease-in-out duration-150 ${service.is_active
                                                            ? 'bg-red-100 border-red-200 text-red-700 hover:bg-red-200'
                                                            : 'bg-green-100 border-green-200 text-green-700 hover:bg-green-200'
                                                        }`}
                                                >
                                                    {service.is_active ? 'Disable' : 'Enable'}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
};

export default MarketingIndex;
