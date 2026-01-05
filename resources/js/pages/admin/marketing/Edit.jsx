import AppLayout from '@/layouts/app-layout.jsx';
import { Head, useForm } from '@inertiajs/react';
import { useState } from 'react';

const MarketingEdit = ({ service, flash }) => {
    const { data, setData, post, processing, errors } = useForm({
        credentials: service.credentials.reduce((acc, cred) => {
            acc[cred.key] = cred.value;
            return acc;
        }, {}),
    });

    const [activeTab, setActiveTab] = useState('credentials');

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('admin.marketing.update-credentials', service.slug));
    };

    const getServiceFields = () => {
        switch (service.slug) {
            case 'google-analytics':
                return (
                    <>
                        <div className="mb-4">
                            <label className="mb-2 block text-sm font-bold text-gray-700" htmlFor="measurement_id">
                                Measurement ID (G-XXXXXXX)
                            </label>
                            <input
                                id="measurement_id"
                                type="text"
                                className="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none"
                                value={data.credentials.measurement_id || ''}
                                onChange={(e) =>
                                    setData('credentials', {
                                        ...data.credentials,
                                        measurement_id: e.target.value,
                                    })
                                }
                                placeholder="G-XXXXXXXXXX"
                            />
                            {errors.credentials && errors.credentials.measurement_id && (
                                <p className="text-xs text-red-500 italic">{errors.credentials.measurement_id}</p>
                            )}
                        </div>
                        <div className="mb-4">
                            <label className="mb-2 block text-sm font-bold text-gray-700" htmlFor="property_id">
                                Property ID
                            </label>
                            <input
                                id="property_id"
                                type="text"
                                className="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none"
                                value={data.credentials.property_id || ''}
                                onChange={(e) =>
                                    setData('credentials', {
                                        ...data.credentials,
                                        property_id: e.target.value,
                                    })
                                }
                            />
                        </div>
                    </>
                );

            case 'google-search-console':
                return (
                    <>
                        <div className="mb-4">
                            <label className="mb-2 block text-sm font-bold text-gray-700" htmlFor="verification_code">
                                Verification Code/Meta Tag
                            </label>
                            <input
                                id="verification_code"
                                type="text"
                                className="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none"
                                value={data.credentials.verification_code || ''}
                                onChange={(e) =>
                                    setData('credentials', {
                                        ...data.credentials,
                                        verification_code: e.target.value,
                                    })
                                }
                                placeholder="google-site-verification=xxxxxxxxxxxxxxxx"
                            />
                        </div>
                    </>
                );

            case 'facebook-pixel':
                return (
                    <>
                        <div className="mb-4">
                            <label className="mb-2 block text-sm font-bold text-gray-700" htmlFor="pixel_id">
                                Facebook Pixel ID
                            </label>
                            <input
                                id="pixel_id"
                                type="text"
                                className="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none"
                                value={data.credentials.pixel_id || ''}
                                onChange={(e) =>
                                    setData('credentials', {
                                        ...data.credentials,
                                        pixel_id: e.target.value,
                                    })
                                }
                                placeholder="XXXXXXXXXX"
                            />
                        </div>
                    </>
                );

            case 'google-tag-manager':
                return (
                    <>
                        <div className="mb-4">
                            <label className="mb-2 block text-sm font-bold text-gray-700" htmlFor="container_id">
                                GTM Container ID
                            </label>
                            <input
                                id="container_id"
                                type="text"
                                className="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none"
                                value={data.credentials.container_id || ''}
                                onChange={(e) =>
                                    setData('credentials', {
                                        ...data.credentials,
                                        container_id: e.target.value,
                                    })
                                }
                                placeholder="GTM-XXXXXXX"
                            />
                        </div>
                    </>
                );

            case 'bing-webmaster':
                return (
                    <>
                        <div className="mb-4">
                            <label className="mb-2 block text-sm font-bold text-gray-700" htmlFor="verification_code">
                                Bing Verification Code
                            </label>
                            <input
                                id="verification_code"
                                type="text"
                                className="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none"
                                value={data.credentials.verification_code || ''}
                                onChange={(e) =>
                                    setData('credentials', {
                                        ...data.credentials,
                                        verification_code: e.target.value,
                                    })
                                }
                                placeholder="XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX"
                            />
                        </div>
                    </>
                );

            default:
                return (
                    <>
                        <div className="mb-4">
                            <label className="mb-2 block text-sm font-bold text-gray-700" htmlFor="custom_script">
                                Custom Script
                            </label>
                            <textarea
                                id="custom_script"
                                className="focus:shadow-outline h-40 w-full appearance-none rounded border px-3 py-2 font-mono leading-tight text-gray-700 shadow focus:outline-none"
                                value={data.credentials.script || ''}
                                onChange={(e) =>
                                    setData('credentials', {
                                        ...data.credentials,
                                        script: e.target.value,
                                    })
                                }
                            />
                        </div>
                        <div className="mb-4">
                            <label className="mb-2 block text-sm font-bold text-gray-700" htmlFor="script_location">
                                Script Location
                            </label>
                            <select
                                id="script_location"
                                className="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none"
                                value={data.credentials.location || 'head'}
                                onChange={(e) =>
                                    setData('credentials', {
                                        ...data.credentials,
                                        location: e.target.value,
                                    })
                                }
                            >
                                <option value="head">Head (Before closing head tag)</option>
                                <option value="body_start">Body Start (After opening body tag)</option>
                                <option value="body_end">Body End (Before closing body tag)</option>
                            </select>
                        </div>
                    </>
                );
        }
    };

    const renderCredentialsTab = () => (
        <form onSubmit={handleSubmit}>
            <div className="mb-6">
                <h2 className="mb-2 text-xl font-semibold">Credentials</h2>
                <p className="text-gray-600">Configure the credentials for {service.name}</p>
            </div>

            {getServiceFields()}

            <div className="flex items-center justify-end">
                <button
                    type="submit"
                    className="focus:shadow-outline rounded bg-blue-600 px-4 py-2 font-bold text-white hover:bg-blue-700 focus:outline-none"
                    disabled={processing}
                >
                    {processing ? 'Saving...' : 'Save Credentials'}
                </button>
            </div>
        </form>
    );

    const renderTrackingScriptsTab = () => (
        <div>
            <div className="mb-6">
                <h2 className="mb-2 text-xl font-semibold">Tracking Scripts</h2>
                <p className="text-gray-600">View generated tracking scripts for {service.name}</p>
            </div>

            {service.tracking_scripts && service.tracking_scripts.length > 0 ? (
                service.tracking_scripts.map((script) => (
                    <div key={script.id} className="mb-6">
                        <h3 className="mb-2 text-lg font-medium">
                            {script.location === 'head' && 'Head Script'}
                            {script.location === 'body_start' && 'Body Start Script'}
                            {script.location === 'body_end' && 'Body End Script'}
                        </h3>
                        <div className="rounded-md bg-gray-100 p-4">
                            <pre className="max-h-60 overflow-auto font-mono text-sm whitespace-pre-wrap">{script.script_content}</pre>
                        </div>
                    </div>
                ))
            ) : (
                <div className="border-l-4 border-yellow-500 bg-yellow-100 p-4 text-yellow-700" role="alert">
                    <p>No tracking scripts have been generated yet. Save your credentials first.</p>
                </div>
            )}
        </div>
    );

    const renderHelpTab = () => {
        switch (service.slug) {
            case 'google-analytics':
                return (
                    <div>
                        <h2 className="mb-4 text-xl font-semibold">How to set up Google Analytics</h2>
                        <ol className="mb-4 list-inside list-decimal space-y-2">
                            <li>
                                Go to{' '}
                                <a href="https://analytics.google.com" target="_blank" className="text-blue-600 hover:underline">
                                    Google Analytics
                                </a>
                            </li>
                            <li>Create a new account or use an existing one</li>
                            <li>Create a new property for your website</li>
                            <li>Copy the Measurement ID (starts with G-)</li>
                            <li>Paste the Measurement ID into the credentials form</li>
                            <li>Save the credentials</li>
                        </ol>
                        <p>Your Google Analytics tracking code will be automatically added to your website.</p>
                    </div>
                );

            case 'facebook-pixel':
                return (
                    <div>
                        <h2 className="mb-4 text-xl font-semibold">How to set up Facebook Pixel</h2>
                        <ol className="mb-4 list-inside list-decimal space-y-2">
                            <li>
                                Go to{' '}
                                <a href="https://business.facebook.com/events_manager" target="_blank" className="text-blue-600 hover:underline">
                                    Facebook Events Manager
                                </a>
                            </li>
                            <li>Create a new pixel or use an existing one</li>
                            <li>Copy the Pixel ID</li>
                            <li>Paste the Pixel ID into the credentials form</li>
                            <li>Save the credentials</li>
                        </ol>
                        <p>Your Facebook Pixel tracking code will be automatically added to your website.</p>
                    </div>
                );

            default:
                return (
                    <div>
                        <h2 className="mb-4 text-xl font-semibold">Setup Instructions</h2>
                        <p>Please refer to the documentation for {service.name} to get the required credentials.</p>
                    </div>
                );
        }
    };

    return (
        <AppLayout>
            <Head title={`Configure ${service.name}`} />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    {flash?.success && (
                        <div className="mb-4 rounded border border-green-400 bg-green-100 px-4 py-3 text-green-700">{flash.success}</div>
                    )}

                    {flash?.error && <div className="mb-4 rounded border border-red-400 bg-red-100 px-4 py-3 text-red-700">{flash.error}</div>}

                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="border-b border-gray-200 bg-white p-6">
                            <div className="mb-6 flex items-center justify-between">
                                <h1 className="text-2xl font-bold">Configure {service.name}</h1>
                                <div className="flex items-center">
                                    <div className={`mr-2 h-3 w-3 rounded-full ${service.is_active ? 'bg-green-500' : 'bg-red-500'}`}></div>
                                    <span className="text-sm text-gray-600">{service.is_active ? 'Active' : 'Inactive'}</span>
                                </div>
                            </div>

                            <div className="mb-6">
                                <div className="border-b border-gray-200">
                                    <nav className="-mb-px flex space-x-8" aria-label="Tabs">
                                        <button
                                            onClick={() => setActiveTab('credentials')}
                                            className={`border-b-2 px-1 py-4 text-sm font-medium ${
                                                activeTab === 'credentials'
                                                    ? 'border-blue-500 text-blue-600'
                                                    : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'
                                            } `}
                                        >
                                            Credentials
                                        </button>
                                        <button
                                            onClick={() => setActiveTab('tracking_scripts')}
                                            className={`border-b-2 px-1 py-4 text-sm font-medium ${
                                                activeTab === 'tracking_scripts'
                                                    ? 'border-blue-500 text-blue-600'
                                                    : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'
                                            } `}
                                        >
                                            Tracking Scripts
                                        </button>
                                        <button
                                            onClick={() => setActiveTab('help')}
                                            className={`border-b-2 px-1 py-4 text-sm font-medium ${
                                                activeTab === 'help'
                                                    ? 'border-blue-500 text-blue-600'
                                                    : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'
                                            } `}
                                        >
                                            Help
                                        </button>
                                    </nav>
                                </div>
                            </div>

                            <div className="py-4">
                                {activeTab === 'credentials' && renderCredentialsTab()}
                                {activeTab === 'tracking_scripts' && renderTrackingScriptsTab()}
                                {activeTab === 'help' && renderHelpTab()}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
};

export default MarketingEdit;
