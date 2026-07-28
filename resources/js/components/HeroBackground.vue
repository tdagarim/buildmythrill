<script setup lang="ts">
import gsap from 'gsap';
import { onMounted, ref } from 'vue';
import aerialLagoonBoat from '../../images/hero/aerial-lagoon-boat.jpg';
import aerialSurferShore from '../../images/hero/aerial-surfer-shore.jpg';
import beachCamperVan from '../../images/hero/beach-camper-van.jpg';
import jungleAtv from '../../images/hero/jungle-atv.jpg';
import jungleBuggyConvoy from '../../images/hero/jungle-buggy-convoy.jpg';
import jungleRiverRaft from '../../images/hero/jungle-river-raft.jpg';
import palmTreeClimbSunset from '../../images/hero/palm-tree-climb-sunset.jpg';
import palmsSurfboardLagoon from '../../images/hero/palms-surfboard-lagoon.jpg';
import tropicalJetty from '../../images/hero/tropical-jetty.jpg';

const images = [
    aerialLagoonBoat,
    aerialSurferShore,
    beachCamperVan,
    jungleAtv,
    jungleBuggyConvoy,
    jungleRiverRaft,
    palmTreeClimbSunset,
    palmsSurfboardLagoon,
    tropicalJetty,
];

const image = images[Math.floor(Math.random() * images.length)];
const backgroundRef = ref<HTMLElement | null>(null);

onMounted(() => {
    if (!backgroundRef.value) {
        return;
    }

    const prefersReducedMotion = window.matchMedia(
        '(prefers-reduced-motion: reduce)',
    ).matches;

    if (prefersReducedMotion) {
        return;
    }

    gsap.set(backgroundRef.value, { opacity: 0 });
    gsap.to(backgroundRef.value, {
        opacity: 1,
        duration: 1.2,
        ease: 'power2.out',
    });
    gsap.to(backgroundRef.value, {
        scale: 1.08,
        duration: 20,
        ease: 'none',
        repeat: -1,
        yoyo: true,
    });
});
</script>

<template>
    <div class="fixed inset-0 z-0 overflow-hidden" aria-hidden="true">
        <div
            ref="backgroundRef"
            class="absolute inset-0 bg-cover bg-center"
            :style="{ backgroundImage: `url(${image})` }"
        />
        <div
            class="absolute inset-0 bg-gradient-to-b from-black/55 via-black/45 to-black/65"
        />
    </div>
</template>
