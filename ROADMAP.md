# Eternal Shard - Roadmap

## Phase 1: Foundations
- [ ] SPA HUD architecture definition (Vue 3, Tailwind CSS, Pinia).
- [ ] Implement initial Database Schema (Users, Characters, Items, Monsters).
- [ ] Scaffold `Character` model, migrations, logic, and tests.

## Phase 2: Real-time Engine
- [ ] Setup Laravel Reverb for WebSockets mapping to Vue application.
- [ ] Implement Global Event Feed (server-to-client broadcasts).
- [ ] Create Presence Channels for "Locations" or "World Zones".

## Phase 3: RPG Systems
- [ ] Server-validated Combat Logic (tick system).
- [ ] Loot Tables formulation and drop chances.
- [ ] Offline Progress Engine (time away * zone rate modifiers).
- [ ] Blacksmith System (Upgrades, success rates, gold sinks).

## Phase 4: Economy & Payments
- [ ] Player Marketplace for P2P item trading.
- [ ] Stripe Integration for one-time "Shard Credits".
- [ ] Stripe Customer Subscriptions for "Supporter Status".
- [ ] Validated Webhook handling.
