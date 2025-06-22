import { z } from "zod";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { router } from '@inertiajs/react';
import { useState, useEffect } from 'react';

import { Button } from "@/components/ui/button";
import {
    Form,
    FormControl,
    FormDescription,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from "@/components/ui/form";
import { Input } from "@/components/ui/input";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";
import { Textarea } from "@/components/ui/textarea";

// Import React FilePond
import { FilePond } from 'react-filepond';
import 'filepond/dist/filepond.min.css';
import { ShowEquipmentListing } from "@/types";

// Predefined lists
const conditions = [
    "New",
    "Like New", 
    "Good",
    "Fair",
    "Poor",
];

// Zod schema for form validation  
const formSchema = z.object({
    title: z.string().min(2, {
        message: "Title must be at least 2 characters.",
    }),
    description: z.string().max(1000, {
        message: "Description must not exceed 1000 characters.",
    }).optional(),
    photos: z.array(z.any()).optional(), // FilePond files array
    condition: z.string().min(1, { message: "Please select a condition." }),
    location: z.string().min(2, {
        message: "Location must be at least 2 characters.",
    }),
    phoneContact: z.string().optional(),
    emailContact: z.string().email("Invalid email address.").optional().or(z.literal("")),
}).refine(
    (data) => data.phoneContact || data.emailContact,
    {
        message: "Either Phone or Email contact is required.",
        path: ["phoneContact"], // Show error on phone field
    }
);

// Infer type from schema
type ShowEquipmentListingFormValues = z.infer<typeof formSchema>;

interface ShowEquipmentListingFormProps {
    showEquipment?: ShowEquipmentListing;
}

export function ShowEquipmentListingForm({ showEquipment }: ShowEquipmentListingFormProps) {
    const [isSubmitting, setIsSubmitting] = useState(false);
    const isEditing = !!showEquipment;

    const form = useForm<ShowEquipmentListingFormValues>({
        resolver: zodResolver(formSchema),
        defaultValues: {
            title: showEquipment?.title || "",
            description: showEquipment?.description || "",
            photos: showEquipment?.photos || [],
            condition: showEquipment?.condition || "",
            location: showEquipment?.location || "",
            phoneContact: showEquipment?.phone_contact || "",
            emailContact: showEquipment?.email_contact || "",
        },
    });

    // Set select fields separately due to controlled component requirements
    useEffect(() => {
        if (showEquipment?.condition) {
            form.setValue('condition', showEquipment.condition);
        }
    }, [showEquipment, form]);

    // Handle form submission
    function onSubmit(values: ShowEquipmentListingFormValues) {
        setIsSubmitting(true);

        const formData = new FormData();

        // Append basic fields
        formData.append('title', values.title);
        formData.append('condition', values.condition);
        formData.append('location', values.location);

        // Append optional fields only if they have values
        if (values.description) formData.append('description', values.description);
        if (values.phoneContact) formData.append('phone_contact', values.phoneContact);
        if (values.emailContact) formData.append('email_contact', values.emailContact);

        // Append photos
        if (values.photos && values.photos.length > 0) {
            values.photos.forEach((file, index) => {
                formData.append(`photos[${index}]`, file);
            });
        }

        // Add _method for PUT request when editing
        if (isEditing && showEquipment) {
            formData.append('_method', 'PUT');
            router.post(`/show-equipment/${showEquipment.id}`, formData, {
                forceFormData: true,
                onFinish: () => setIsSubmitting(false),
                onError: (errors) => {
                    console.error('Form validation errors:', errors);
                    setIsSubmitting(false);
                },
            });
        } else {
            router.post('/show-equipment', formData, {
                forceFormData: true,
                onFinish: () => setIsSubmitting(false),
                onError: (errors) => {
                    console.error('Form validation errors:', errors);
                    setIsSubmitting(false);
                },
            });
        }
    }

    return (
        <Form {...form}>
            <form onSubmit={form.handleSubmit(onSubmit)} className="space-y-8">
                {/* Basic Information Section */}
                <div className="space-y-6">
                    <div className="pb-4 border-b border-border">
                        <h3 className="text-lg font-semibold">Basic Information</h3>
                        <p className="text-sm text-muted-foreground">Essential details about your show equipment</p>
                    </div>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <FormField
                            control={form.control}
                            name="title"
                            render={({ field }) => (
                                <FormItem className="md:col-span-2">
                                    <FormLabel>Title</FormLabel>
                                    <FormControl>
                                        <Input placeholder="e.g., Professional Show Halter - Like New" {...field} />
                                    </FormControl>
                                    <FormDescription>
                                        A descriptive title for your show equipment.
                                    </FormDescription>
                                    <FormMessage />
                                </FormItem>
                            )}
                        />

                        <FormField
                            control={form.control}
                            name="condition"
                            render={({ field }) => (
                                <FormItem>
                                    <FormLabel>Condition</FormLabel>
                                    <Select onValueChange={field.onChange} defaultValue={field.value}>
                                        <FormControl>
                                            <SelectTrigger>
                                                <SelectValue placeholder="Select condition" />
                                            </SelectTrigger>
                                        </FormControl>
                                        <SelectContent>
                                            {conditions.map((condition) => (
                                                <SelectItem key={condition} value={condition}>
                                                    {condition}
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>
                                    <FormDescription>
                                        The current condition of the equipment.
                                    </FormDescription>
                                    <FormMessage />
                                </FormItem>
                            )}
                        />

                        <FormField
                            control={form.control}
                            name="location"
                            render={({ field }) => (
                                <FormItem>
                                    <FormLabel>Location</FormLabel>
                                    <FormControl>
                                        <Input placeholder="e.g., Tamworth, NSW" {...field} />
                                    </FormControl>
                                    <FormDescription>
                                        Your town and state.
                                    </FormDescription>
                                    <FormMessage />
                                </FormItem>
                            )}
                        />
                    </div>
                </div>

                {/* Photos Section */}
                <div className="space-y-6">
                    <div className="pb-4 border-b border-border">
                        <h3 className="text-lg font-semibold">Photos</h3>
                        <p className="text-sm text-muted-foreground">Upload images of your show equipment</p>
                    </div>
                    <FormField
                        control={form.control}
                        name="photos"
                        render={({ field: { onChange, value } }) => (
                            <FormItem>
                                <FormLabel>Photos</FormLabel>
                                <FormControl>
                                    <FilePond
                                        files={value || []}
                                        onupdatefiles={(fileItems) => {
                                            onChange(fileItems.map(fileItem => fileItem.file));
                                        }}
                                        allowMultiple={true}
                                        maxFiles={10}
                                        labelIdle='Drag & Drop your photos or <span class="filepond--label-action">Browse</span>'
                                    />
                                </FormControl>
                                <FormDescription>
                                    Upload photos of your show equipment (up to 10 images).
                                </FormDescription>
                                <FormMessage />
                            </FormItem>
                        )}
                    />
                </div>

                {/* Contact Information Section */}
                <div className="space-y-6">
                    <div className="pb-4 border-b border-border">
                        <h3 className="text-lg font-semibold">Contact Information</h3>
                        <p className="text-sm text-muted-foreground">How buyers can reach you</p>
                    </div>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <FormField
                            control={form.control}
                            name="phoneContact"
                            render={({ field }) => (
                                <FormItem>
                                    <FormLabel>Phone Contact (Required if no email)</FormLabel>
                                    <FormControl>
                                        <Input type="tel" placeholder="e.g., 0412345678" {...field} />
                                    </FormControl>
                                    <FormDescription>
                                        Your phone number for contact.
                                    </FormDescription>
                                    <FormMessage />
                                </FormItem>
                            )}
                        />

                        <FormField
                            control={form.control}
                            name="emailContact"
                            render={({ field }) => (
                                <FormItem>
                                    <FormLabel>Email Contact (Required if no phone)</FormLabel>
                                    <FormControl>
                                        <Input type="email" placeholder="e.g., contact@example.com" {...field} />
                                    </FormControl>
                                    <FormDescription>
                                        Your email address for contact.
                                    </FormDescription>
                                    <FormMessage />
                                </FormItem>
                            )}
                        />
                    </div>
                </div>

                {/* Additional Details Section */}
                <div className="space-y-6">
                    <div className="pb-4 border-b border-border">
                        <h3 className="text-lg font-semibold">Additional Details</h3>
                        <p className="text-sm text-muted-foreground">Extra information about the equipment</p>
                    </div>

                    <FormField
                        control={form.control}
                        name="description"
                        render={({ field }) => (
                            <FormItem>
                                <FormLabel>Description (Optional)</FormLabel>
                                <FormControl>
                                    <Textarea
                                        placeholder="Describe the show equipment, its features, any included accessories, or other relevant details..."
                                        className="resize-y min-h-[100px]"
                                        {...field}
                                    />
                                </FormControl>
                                <FormDescription>
                                    Provide a detailed description (max 1000 characters).
                                </FormDescription>
                                <FormMessage />
                            </FormItem>
                        )}
                    />
                </div>

                <div className="flex justify-end pt-6 border-t border-border">
                    <Button type="submit" size="lg" className="min-w-[120px]" disabled={isSubmitting}>
                        {isSubmitting
                            ? (isEditing ? 'Updating...' : 'Creating...')
                            : (isEditing ? 'Update Listing' : 'Create Listing')
                        }
                    </Button>
                </div>
            </form>
        </Form>
    );
}