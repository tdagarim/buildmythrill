<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import HeroBackground from '@/components/HeroBackground.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { store } from '@/routes/waitlist';
</script>

<template>
    <Head title="Coming Soon" />

    <div
        class="relative flex min-h-screen items-center justify-center p-6"
    >
        <HeroBackground />

        <div
            class="relative z-10 flex w-full max-w-lg flex-col items-center px-6 text-center text-white"
        >
            <AppLogoIcon class="h-20 w-auto drop-shadow-lg sm:h-28" />

            <h1
                class="mt-8 text-3xl font-semibold tracking-tight drop-shadow-sm sm:text-4xl"
            >
                Something thrilling<br />
                is coming.
            </h1>
            <p class="mt-3 text-white/80 drop-shadow-sm">
                Be the first to know when we launch.
            </p>

            <Form
                v-bind="store.form()"
                reset-on-success
                v-slot="{ errors, processing, recentlySuccessful }"
                class="mt-8 w-full max-w-sm"
            >
                <p
                    v-if="recentlySuccessful"
                    class="text-sm font-medium text-white"
                >
                    You're on the list — we'll be in touch.
                </p>
                <div v-else class="flex flex-col gap-2 sm:flex-row">
                    <div class="flex-1 text-left">
                        <label for="email" class="sr-only"
                            >Email address</label
                        >
                        <Input
                            id="email"
                            type="email"
                            required
                            autocomplete="email"
                            name="email"
                            placeholder="you@example.com"
                            class="border-white/20 bg-white/10 text-white placeholder:text-white/50 focus-visible:ring-white/40"
                        />
                        <p
                            v-if="errors.email"
                            class="mt-2 text-left text-sm text-red-300"
                        >
                            {{ errors.email }}
                        </p>
                    </div>
                    <Button
                        type="submit"
                        :disabled="processing"
                        class="shrink-0"
                    >
                        Notify me
                    </Button>
                </div>
            </Form>
        </div>
    </div>
</template>
