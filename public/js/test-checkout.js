
    const subtotal = {{ $subtotal }};
    const discount = {{ $discountAmount }};
    const freeShippingMin = {{ $freeShippingMin }};

    // Store & Routing Configuration
    const STORE_LAT = parseFloat("{{ $storeSettings['store_latitude'] ?? '-7.2654' }}");
    const STORE_LNG = parseFloat("{{ $storeSettings['store_longitude'] ?? '112.7483' }}");
    const STORE_NAME = "{{ $storeSettings['store_name'] ?? 'Desy Florist' }}";
    const STORE_DISTRICT = "{{ $storeSettings['store_district'] ?? 'Surabaya Pusat' }}";
    const ENABLED_DISTRICTS = @json($enabledDistricts);
    const MAX_SERVICE_DISTANCE = parseFloat("{{ $maxDistanceKm }}") || 25.0;
    const UNSERVICED_MESSAGE = "{{ addslashes($unservicedMessage) }}";

    // Global reference to full Indonesian regions & delivery zones
    const INDONESIA_REGIONS = window.INDONESIA_REGIONS || [];
    const DELIVERY_ZONES = @json($deliveryZones);

    // Current selection state
    let state = {
        province: document.getElementById('selected_province').value || 'Jawa Timur',
        city: document.getElementById('selected_city').value || 'Kota Surabaya',
        district: document.getElementById('selected_district').value || 'Bubutan',
        subdistrict: document.getElementById('selected_subdistrict').value || 'Bubutan',
        postalCode: document.getElementById('selected_postal_code').value || '60174',
        currentStep: 'province'
    };

    // Modal Control
    function openRegionModal() {
        document.getElementById('regionModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        switchModalStep(state.currentStep || 'province');
    }

    function closeRegionModal() {
        document.getElementById('regionModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        document.getElementById('regionSearchInput').value = '';
        document.getElementById('searchResultsBox').classList.add('hidden');
    }

    function switchModalStep(step) {
        state.currentStep = step;
        const tabs = ['province', 'city', 'district', 'subdistrict'];
        tabs.forEach(t => {
            const btn = document.getElementById('tab' + t.charAt(0).toUpperCase() + t.slice(1));
            if (btn) {
                if (t === step) {
                    btn.className = 'flex-1 py-2.5 px-3 text-center font-bold text-[#B85D66] border-b-2 border-[#B85D66] bg-white whitespace-nowrap transition';
                } else {
                    btn.className = 'flex-1 py-2.5 px-3 text-center text-[#6B6565] whitespace-nowrap hover:text-[#2A2626] transition';
                }
            }
        });

        // Update Breadcrumb
        updateBreadcrumb();
        renderModalList(step);
    }

    function updateBreadcrumb() {
        const parts = [];
        if (state.province) parts.push(state.province);
        if (state.city) parts.push(state.city);
        if (state.district) parts.push('Kec. ' + state.district);
        if (state.subdistrict) parts.push('Kel. ' + state.subdistrict + (state.postalCode ? ' (' + state.postalCode + ')' : ''));
        document.getElementById('crumbProvince').textContent = parts.length ? parts.join(' â€º ') : 'Pilih Provinsi';
    }

    function renderModalList(step) {
        const listEl = document.getElementById('modalItemsList');
        listEl.innerHTML = '';

        if (step === 'province') {
            INDONESIA_REGIONS.forEach(p => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = `w-full p-3.5 text-left flex items-center justify-between hover:bg-[#F8E8EA]/40 transition ${state.province === p.province ? 'bg-[#FAF8F5] font-bold text-[#B85D66]' : 'text-[#2A2626]'}`;
                btn.innerHTML = `<span>${p.province}</span><span class="text-[#9B9494] text-xs">â€º</span>`;
                btn.onclick = () => selectProvince(p.province);
                listEl.appendChild(btn);
            });
        } else if (step === 'city') {
            const curProv = INDONESIA_REGIONS.find(p => p.province === state.province) || INDONESIA_REGIONS[0];
            curProv.cities.forEach(c => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = `w-full p-3.5 text-left flex items-center justify-between hover:bg-[#F8E8EA]/40 transition ${state.city === c.name ? 'bg-[#FAF8F5] font-bold text-[#B85D66]' : 'text-[#2A2626]'}`;
                btn.innerHTML = `<span>${c.name}</span><span class="text-[#9B9494] text-xs">â€º</span>`;
                btn.onclick = () => selectCity(c.name);
                listEl.appendChild(btn);
            });
        } else if (step === 'district') {
            const curProv = INDONESIA_REGIONS.find(p => p.province === state.province) || INDONESIA_REGIONS[0];
            const curCity = curProv.cities.find(c => c.name === state.city) || curProv.cities[0];
            curCity.districts.forEach(d => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = `w-full p-3.5 text-left flex items-center justify-between hover:bg-[#F8E8EA]/40 transition ${state.district === d.name ? 'bg-[#FAF8F5] font-bold text-[#B85D66]' : 'text-[#2A2626]'}`;
                btn.innerHTML = `<span>Kec. ${d.name}</span><span class="text-[11px] text-[#9B9494]">Kode Pos: ${d.postalCode} â€º</span>`;
                btn.onclick = () => selectDistrict(d.name, d.postalCode);
                listEl.appendChild(btn);
            });
        } else if (step === 'subdistrict') {
            const curProv = INDONESIA_REGIONS.find(p => p.province === state.province) || INDONESIA_REGIONS[0];
            const curCity = curProv ? (curProv.cities.find(c => c.name === state.city) || curProv.cities[0]) : null;
            const curDist = curCity ? (curCity.districts.find(d => d.name === state.district) || curCity.districts[0]) : null;
            const postalCode = curDist ? curDist.postalCode : '60174';

            if (curDist && curDist.subDistricts && curDist.subDistricts.length > 0) {
                curDist.subDistricts.forEach(sub => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = `w-full p-3.5 text-left flex items-center justify-between hover:bg-[#F8E8EA]/40 transition ${state.subdistrict === sub ? 'bg-[#FAF8F5] font-bold text-[#B85D66]' : 'text-[#2A2626]'}`;
                    btn.innerHTML = `<span>Kel. ${sub}</span><span class="text-[11px] font-mono text-[#5C6A57]">âœ“ Pilih (${postalCode})</span>`;
                    btn.onclick = () => selectSubDistrict(sub, postalCode);
                    listEl.appendChild(btn);
                });
            }

            // Custom manual input option for any unlisted village/kelurahan
            const customDiv = document.createElement('div');
            customDiv.className = 'p-3 bg-[#FAF8F5] border-t border-[#EADFD4] flex flex-col gap-2 mt-2 rounded-xl mx-2 mb-2';
            customDiv.innerHTML = `
                <div class="text-[11px] text-[#6B6565] font-semibold flex items-center gap-1.5">
                    <span>âœï¸</span>
                    <span>Kelurahan/Desa Anda tidak ada di daftar?</span>
                </div>
                <div class="flex gap-2">
                    <input type="text" id="customSubDistrictInput" placeholder="Ketik nama kelurahan / desa..." class="flex-1 p-2 rounded-lg border border-[#D8BABF] bg-white text-xs focus:outline-none focus:ring-1 focus:ring-[#B85D66]">
                    <button type="button" onclick="submitCustomSubDistrict('${postalCode}')" class="px-3.5 py-2 bg-[#B85D66] text-white font-semibold rounded-lg text-xs hover:bg-[#A24D56] transition shadow-2xs">
                        Gunakan
                    </button>
                </div>
            `;
            listEl.appendChild(customDiv);
        }
    }

    function submitCustomSubDistrict(defaultPostal) {
        const inp = document.getElementById('customSubDistrictInput');
        const val = inp ? inp.value.trim() : '';
        if (!val) {
            alert('Silakan ketik nama kelurahan atau desa Anda.');
            return;
        }
        selectSubDistrict(val, defaultPostal);
    }

    function selectProvince(prov) {
        state.province = prov;
        const pObj = INDONESIA_REGIONS.find(p => p.province === prov);
        state.city = pObj.cities[0].name;
        state.district = pObj.cities[0].districts[0].name;
        state.subdistrict = (pObj.cities[0].districts[0].subDistricts && pObj.cities[0].districts[0].subDistricts[0]) ? pObj.cities[0].districts[0].subDistricts[0] : pObj.cities[0].districts[0].name;
        state.postalCode = pObj.cities[0].districts[0].postalCode;
        switchModalStep('city');
    }

    function selectCity(city) {
        state.city = city;
        const pObj = INDONESIA_REGIONS.find(p => p.province === state.province);
        const cObj = pObj.cities.find(c => c.name === city);
        state.district = cObj.districts[0].name;
        state.subdistrict = (cObj.districts[0].subDistricts && cObj.districts[0].subDistricts[0]) ? cObj.districts[0].subDistricts[0] : cObj.districts[0].name;
        state.postalCode = cObj.districts[0].postalCode;
        switchModalStep('district');
    }

    function selectDistrict(dist, postal) {
        state.district = dist;
        state.postalCode = postal;
        const pObj = INDONESIA_REGIONS.find(p => p.province === state.province);
        const cObj = pObj ? pObj.cities.find(c => c.name === state.city) : null;
        const dObj = cObj ? cObj.districts.find(d => d.name === dist) : null;
        state.subdistrict = (dObj && dObj.subDistricts && dObj.subDistricts[0]) ? dObj.subDistricts[0] : dist;
        switchModalStep('subdistrict');
    }

    function selectSubDistrict(sub, postal) {
        state.subdistrict = sub;
        state.postalCode = postal;
        applySelectedRegion();
        closeRegionModal();
    }

    // Live Instant Search across all regions
    function onRegionSearch(query) {
        const box = document.getElementById('searchResultsBox');
        if (!query || query.trim().length < 2) {
            box.classList.add('hidden');
            box.innerHTML = '';
            return;
        }

        const q = query.toLowerCase().trim();
        const matches = [];

        INDONESIA_REGIONS.forEach(p => {
            p.cities.forEach(c => {
                c.districts.forEach(d => {
                    const matchDist = d.name.toLowerCase().includes(q);
                    const matchCity = c.name.toLowerCase().includes(q);
                    const matchProv = p.province.toLowerCase().includes(q);
                    const matchPostal = d.postalCode && d.postalCode.includes(q);
                    const matchSub = d.subDistricts && d.subDistricts.some(s => s.toLowerCase().includes(q));

                    if (matchDist || matchCity || matchProv || matchPostal || matchSub) {
                        const matchedSubName = (matchSub && d.subDistricts.find(s => s.toLowerCase().includes(q))) || (d.subDistricts ? d.subDistricts[0] : d.name);
                        matches.push({
                            province: p.province,
                            city: c.name,
                            district: d.name,
                            subdistrict: matchedSubName,
                            postalCode: d.postalCode
                        });
                    }
                });
            });
        });

        if (matches.length === 0) {
            box.innerHTML = '<div class="p-3 text-[11px] text-[#9B9494] text-center">Tidak ditemukan wilayah yang sesuai. Coba ketik nama kota atau kecamatan.</div>';
            box.classList.remove('hidden');
            return;
        }

        box.innerHTML = '';
        matches.slice(0, 15).forEach(m => {
            const item = document.createElement('div');
            item.className = 'p-2.5 hover:bg-[#F8E8EA]/40 cursor-pointer flex items-center justify-between text-xs transition border-b border-[#F3ECE4] last:border-0';
            item.innerHTML = `
                <div>
                    <span class="font-bold text-[#2A2626] block">Kec. ${m.district}, ${m.city}</span>
                    <span class="text-[10px] text-[#6B6565]">${m.province} â€¢ Kel. ${m.subdistrict} â€¢ Kode Pos: ${m.postalCode}</span>
                </div>
                <span class="text-xs text-[#B85D66] font-bold">Pilih â€º</span>
            `;
            item.onclick = () => {
                state.province = m.province;
                state.city = m.city;
                state.district = m.district;
                state.subdistrict = m.subdistrict;
                state.postalCode = m.postalCode;
                applySelectedRegion();
                closeRegionModal();
            };
            box.appendChild(item);
        });

        box.classList.remove('hidden');
    }

    // Apply & Synchronize Selected Region
    function applySelectedRegion() {
        // Update hidden inputs
        document.getElementById('selected_province').value = state.province;
        document.getElementById('selected_city').value = state.city;
        document.getElementById('selected_district').value = state.district;
        document.getElementById('selected_subdistrict').value = state.subdistrict;
        document.getElementById('selected_postal_code').value = state.postalCode;

        // Update trigger display text
        const displayLabel = `${state.province}, ${state.city}, Kec. ${state.district}, Kel. ${state.subdistrict} (${state.postalCode})`;
        document.getElementById('selectedRegionText').innerHTML = `
            <span class="font-bold text-[#2A2626]">${displayLabel}</span>
        `;
        document.getElementById('regionActionText').textContent = 'Ubah';

        // Sync inline selects
        syncInlineSelects();

        // Auto select best Delivery Zone in Sectio        // Update final full address
        syncCombinedAddress();

        // Update routing map destination to selected district
        syncMapToDistrict(state.district);
    }

    // Coordinates of 31 Surabaya Districts for Initial Pin Anchoring
    const SURABAYA_DISTRICT_COORDS = {
        'Tegalsari': [-7.2721, 112.7380],
        'Genteng': [-7.2604, 112.7483],
        'Bubutan': [-7.2519, 112.7320],
        'Simokerto': [-7.2405, 112.7538],
        'Gubeng': [-7.2764, 112.7548],
        'Gunung Anyar': [-7.3361, 112.7936],
        'Sukolilo': [-7.2882, 112.7871],
        'Tambaksari': [-7.2529, 112.7681],
        'Mulyorejo': [-7.2683, 112.7936],
        'Rungkut': [-7.3218, 112.7758],
        'Tenggilis Mejoyo': [-7.3197, 112.7525],
        'Benowo': [-7.2343, 112.6373],
        'Pakal': [-7.2335, 112.6087],
        'Asemrowo': [-7.2465, 112.7042],
        'Sukomanunggal': [-7.2736, 112.7051],
        'Tandes': [-7.2612, 112.6738],
        'Sambikerep': [-7.2785, 112.6517],
        'Lakarsantri': [-7.3134, 112.6508],
        'Bulak': [-7.2376, 112.7951],
        'Kenjeran': [-7.2330, 112.7750],
        'Semampir': [-7.2215, 112.7523],
        'Pabean Cantian': [-7.2323, 112.7371],
        'Krembangan': [-7.2378, 112.7242],
        'Wonokromo': [-7.2974, 112.7351],
        'Wonocolo': [-7.3184, 112.7352],
        'Wiyung': [-7.3061, 112.6936],
        'Karang Pilang': [-7.3375, 112.7032],
        'Jambangan': [-7.3204, 112.7161],
        'Gayungan': [-7.3308, 112.7275],
        'Dukuh Pakis': [-7.2891, 112.7054],
        'Sawahan': [-7.2763, 112.7214]
    };

    function calculateHaversineDistance(lat1, lon1, lat2, lon2) {
        const R = 6371; // Earth radius in KM
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                  Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                  Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c;
    }

    // Auto-match delivery zone & time slots based on chosen district / coordinates
    function autoSelectDeliveryZone(districtName, cityName) {
        if (!DELIVERY_ZONES || DELIVERY_ZONES.length === 0) return;

        const dName = (districtName || '').trim();
        const cName = (cityName || '').toLowerCase().trim();

        // 1. Verify if district is in admin's enabled districts list
        const isSurabaya = cName.includes('surabaya');
        const isDistrictEnabled = !isSurabaya || ENABLED_DISTRICTS.some(d => d.toLowerCase() === dName.toLowerCase());

        const alertBox = document.getElementById('unservicedAlertBox');
        const submitBtn = document.getElementById('submitOrderBtn');
        const feeSummaryEl = document.getElementById('activeZoneFee');
        const zoneNameEl = document.getElementById('activeZoneName');
        const zoneDescEl = document.getElementById('activeZoneDescription');

        // Get current recipient coordinates
        const curLat = parseFloat(document.getElementById('recipient_latitude').value) || -7.2519;
        const curLng = parseFloat(document.getElementById('recipient_longitude').value) || 112.7320;

        // Calculate Road Distance (Haversine * Urban detour factor 1.32)
        const straightDist = calculateHaversineDistance(STORE_LAT, STORE_LNG, curLat, curLng);
        const roadDistanceKm = parseFloat((straightDist * 1.32).toFixed(1));
        const estDurationMinutes = Math.max(15, Math.round(roadDistanceKm * 2.6 + 8));

        // Update UI Info
        document.getElementById('distance_km').value = roadDistanceKm;
        const distEl = document.getElementById('routeDistanceText');
        const durEl = document.getElementById('routeDurationText');
        if (distEl) distEl.textContent = `~${roadDistanceKm} KM`;
        if (durEl) durEl.textContent = `~${estDurationMinutes - 5} - ${estDurationMinutes + 10} Menit`;

        // Check unserviced conditions: district disabled OR distance exceeds max radius
        if (!isDistrictEnabled || roadDistanceKm > MAX_SERVICE_DISTANCE) {
            if (alertBox) {
                alertBox.classList.remove('hidden');
                document.getElementById('unservicedTitle').textContent = !isDistrictEnabled 
                    ? `Kecamatan ${dName} Belum Masuk Area Operasional` 
                    : `Jarak Pengiriman (${roadDistanceKm} KM) di Luar Jangkauan Toko`;
                document.getElementById('unservicedDesc').textContent = !isDistrictEnabled
                    ? `Mohon maaf, operasional pengiriman Desy Florist saat ini belum mencakup Kecamatan ${dName}. Admin dapat mengaktifkan wilayah ini melalui dashboard.`
                    : `${UNSERVICED_MESSAGE} (Radius maksimal layanan saat ini adalah ${MAX_SERVICE_DISTANCE} KM dari toko di ${STORE_DISTRICT}).`;
            }

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.classList.remove('bg-[#B85D66]', 'hover:bg-[#8F3B44]');
                submitBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
                submitBtn.textContent = 'Alamat di Luar Area Layanan Florist';
            }

            if (zoneNameEl) zoneNameEl.textContent = 'Di Luar Area Jangkauan Layanan';
            if (zoneDescEl) zoneDescEl.textContent = 'Alamat belum dapat dilayani untuk pengiriman langsung toko saat ini.';
            if (feeSummaryEl) feeSummaryEl.textContent = 'Tidak Tersedia';

            document.getElementById('shippingFeeLabel').innerHTML = '<span class="text-red-600 font-bold">Tidak Tersedia</span>';
            return;
        }

        // District is ENABLED and reachable!
        if (alertBox) alertBox.classList.add('hidden');
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
            submitBtn.classList.add('bg-[#B85D66]', 'hover:bg-[#8F3B44]');
            submitBtn.textContent = 'Konfirmasi & Buat Pesanan';
        }

        // 2. Select zone by distance range (min_distance_km to max_distance_km)
        let matchedZone = DELIVERY_ZONES.find(z => {
            const minKm = parseFloat(z.min_distance_km) || 0;
            const maxKm = parseFloat(z.max_distance_km) || 50;
            return roadDistanceKm >= minKm && roadDistanceKm <= maxKm;
        });

        // Fallback: match by area name or take first zone
        if (!matchedZone) {
            matchedZone = DELIVERY_ZONES.find(z => {
                if (!z.areas || !Array.isArray(z.areas)) return false;
                return z.areas.some(a => a.toLowerCase().includes(dName.toLowerCase()));
            }) || DELIVERY_ZONES[0];
        }

        if (matchedZone) {
            applyZoneSelection(matchedZone, dName, cityName, roadDistanceKm, estDurationMinutes);
        }
    }

    function applyZoneSelection(zone, districtName, cityName, distanceKm, durationMinutes) {
        // Check target radio
        const targetRadio = document.querySelector(`input[name="delivery_zone_id"][value="${zone.id}"]`);
        if (targetRadio) {
            targetRadio.checked = true;
        }

        // Update Active Zone Summary Card in Section 2
        const nameEl = document.getElementById('activeZoneName');
        const descEl = document.getElementById('activeZoneDescription');
        const feeEl = document.getElementById('activeZoneFee');
        const indEl = document.getElementById('matchedAreaIndicator');

        if (nameEl) nameEl.textContent = zone.name;
        if (descEl) {
            const locText = (districtName && cityName) ? `Kec. ${districtName}, ${cityName}` : (cityName || 'Wilayah Pengiriman Anda');
            const distInfo = distanceKm ? ` (Jarak: ${distanceKm} KM â€¢ Estimasi: ~${durationMinutes} menit)` : '';
            descEl.textContent = `Area pengantaran dan kurir aktif untuk ${locText}${distInfo}. Dihitung dari toko di ${STORE_DISTRICT}.`;
        }
        if (feeEl) {
            feeEl.textContent = 'Rp ' + Number(zone.delivery_fee).toLocaleString('id-ID');
        }
        if (indEl) {
            indEl.textContent = `âœ“ Terhubung Otomatis (${zone.name.split(':')[0]})`;
        }

        // Filter and highlight zone cards
        updateZoneCardsVisibility(zone, cityName);

        // Update Dynamic Delivery Slots (Waktu Pengiriman)
        updateDeliverySlots(zone, districtName, cityName);

        // Update Fee & Grand Total Calculation
        updateDeliveryFee();
    }“ Terhubung Otomatis dengan ${districtName || cityName}`;
        }

        // Filter and highlight zone cards
        updateZoneCardsVisibility(zone, cityName);

        // Update Dynamic Delivery Slots (Waktu Pengiriman)
        updateDeliverySlots(zone, districtName, cityName);

        // Update Fee & Grand Total Calculation
        updateDeliveryFee();
    }

    function onManualZoneChange(zoneId) {
        const zone = DELIVERY_ZONES.find(z => z.id === zoneId);
        if (zone) {
            applyZoneSelection(zone, state.district, state.city);
        }
    }

    // Dynamic Delivery Slots Populator
    function updateDeliverySlots(zone, districtName, cityName) {
        const select = document.getElementById('delivery_slot');
        if (!select) return;

        select.innerHTML = '';
        const slots = (zone && zone.available_slots && zone.available_slots.length > 0)
            ? zone.available_slots
            : ['09:00 - 12:00', '13:00 - 16:00', '16:00 - 19:00', '19:00 - 21:00'];

        slots.forEach((s, idx) => {
            const opt = document.createElement('option');
            opt.value = s;

            let label = s + ' WIB';
            if (s.includes('09:00') && s.includes('12:00')) label = `Pagi (${s} WIB)`;
            else if (s.includes('10:00') && s.includes('14:00')) label = `Siang (${s} WIB)`;
            else if (s.includes('13:00') && s.includes('16:00')) label = `Siang (${s} WIB)`;
            else if (s.includes('14:00') && s.includes('18:00')) label = `Sore (${s} WIB)`;
            else if (s.includes('16:00') && s.includes('19:00')) label = `Sore (${s} WIB)`;
            else if (s.includes('19:00') && s.includes('21:00')) label = `Malam (${s} WIB)`;
            else if (s.includes('11:00') && s.includes('16:00')) label = `Reguler / Siang (${s} WIB)`;
            else if (s.includes('10:00') && s.includes('16:00')) label = `Reguler Jam Kerja (${s} WIB)`;

            opt.textContent = label;
            if (idx === 0) opt.selected = true;
            select.appendChild(opt);
        });

        const helper = document.getElementById('slotHelperText');
        if (helper) {
            helper.textContent = `Pilihan slot jam pengantaran aktif khusus untuk area: ${zone.name}`;
        }
    }

    // Filter & highlight relevant cards
    let showAllZones = false;
    function updateZoneCardsVisibility(activeZone, cityName) {
        const cards = document.querySelectorAll('.zone-card');
        const cName = (cityName || '').toLowerCase();

        cards.forEach(card => {
            const zId = parseInt(card.getAttribute('data-zone-id'));
            const zAreas = (card.getAttribute('data-areas') || '').toLowerCase();
            const zName = (card.getAttribute('data-zone-name') || '').toLowerCase();
            const badge = document.getElementById('zoneMatchBadge-' + zId);

            const isMatched = (zId === activeZone.id);
            if (badge) {
                badge.classList.toggle('hidden', !isMatched);
            }

            // Highlight border if active
            if (isMatched) {
                card.classList.add('border-[#B85D66]', 'bg-[#F8E8EA]/40');
            } else {
                card.classList.remove('border-[#B85D66]', 'bg-[#F8E8EA]/40');
            }

            // Determine if card is relevant to current city
            let isRelevant = isMatched;
            if (cName.includes('surabaya') && zName.includes('surabaya')) isRelevant = true;
            if (cName.includes('sidoarjo') && (zName.includes('sidoarjo') || zAreas.includes('sidoarjo'))) isRelevant = true;
            if (cName.includes('jakarta') && zName.includes('jakarta')) isRelevant = true;
            if (cName.includes('tangerang') && (zName.includes('tangerang') || zAreas.includes('tangerang'))) isRelevant = true;
            if ((cName.includes('bekasi') || cName.includes('depok')) && (zName.includes('bekasi') || zName.includes('depok'))) isRelevant = true;
            if (zName.includes('lainnya')) isRelevant = true;

            if (showAllZones || isRelevant) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        });

        const toggleBtn = document.getElementById('toggleAllZonesBtn');
        if (toggleBtn) {
            toggleBtn.textContent = showAllZones ? 'Tampilkan Zona Sesuai Wilayah Saja' : 'Lihat Semua Zona Lainnya';
        }
    }

    function toggleAllZones() {
        showAllZones = !showAllZones;
        const checkedRadio = document.querySelector('input[name="delivery_zone_id"]:checked');
        const activeZone = checkedRadio ? DELIVERY_ZONES.find(z => z.id === parseInt(checkedRadio.value)) : DELIVERY_ZONES[0];
        updateZoneCardsVisibility(activeZone || DELIVERY_ZONES[0], state.city);
    }

    // Inline Dropdowns Support
    function toggleInlineDropdowns() {
        const c = document.getElementById('inlineDropdownsContainer');
        c.classList.toggle('hidden');
        document.getElementById('toggleDropdownIcon').textContent = c.classList.contains('hidden') ? 'â–¼' : 'â–²';
    }

    function initInlineDropdowns() {
        const pSel = document.getElementById('inlineProvinceSelect');
        pSel.innerHTML = '';
        INDONESIA_REGIONS.forEach(p => {
            const opt = document.createElement('option');
            opt.value = p.province;
            opt.textContent = p.province;
            if (p.province === state.province) opt.selected = true;
            pSel.appendChild(opt);
        });
        syncInlineCities();
    }

    function syncInlineCities() {
        const cSel = document.getElementById('inlineCitySelect');
        cSel.innerHTML = '';
        const pObj = INDONESIA_REGIONS.find(p => p.province === state.province) || INDONESIA_REGIONS[0];
        pObj.cities.forEach(c => {
            const opt = document.createElement('option');
            opt.value = c.name;
            opt.textContent = c.name;
            if (c.name === state.city) opt.selected = true;
            cSel.appendChild(opt);
        });
        syncInlineDistricts();
    }

    function syncInlineDistricts() {
        const dSel = document.getElementById('inlineDistrictSelect');
        dSel.innerHTML = '';
        const pObj = INDONESIA_REGIONS.find(p => p.province === state.province) || INDONESIA_REGIONS[0];
        const cObj = pObj.cities.find(c => c.name === state.city) || pObj.cities[0];
        cObj.districts.forEach(d => {
            const opt = document.createElement('option');
            opt.value = d.name;
            opt.textContent = `Kec. ${d.name} (${d.postalCode})`;
            if (d.name === state.district) opt.selected = true;
            dSel.appendChild(opt);
        });
    }

    function onInlineProvinceChange() {
        state.province = document.getElementById('inlineProvinceSelect').value;
        const pObj = INDONESIA_REGIONS.find(p => p.province === state.province);
        state.city = pObj.cities[0].name;
        state.district = pObj.cities[0].districts[0].name;
        state.subdistrict = pObj.cities[0].districts[0].subDistricts[0];
        state.postalCode = pObj.cities[0].districts[0].postalCode;
        syncInlineCities();
        applySelectedRegion();
    }

    function onInlineCityChange() {
        state.city = document.getElementById('inlineCitySelect').value;
        const pObj = INDONESIA_REGIONS.find(p => p.province === state.province);
        const cObj = pObj.cities.find(c => c.name === state.city);
        state.district = cObj.districts[0].name;
        state.subdistrict = cObj.districts[0].subDistricts[0];
        state.postalCode = cObj.districts[0].postalCode;
        syncInlineDistricts();
        applySelectedRegion();
    }

    function onInlineDistrictChange() {
        state.district = document.getElementById('inlineDistrictSelect').value;
        const pObj = INDONESIA_REGIONS.find(p => p.province === state.province);
        const cObj = pObj.cities.find(c => c.name === state.city);
        const dObj = cObj.districts.find(d => d.name === state.district);
        state.subdistrict = dObj.subDistricts[0];
        state.postalCode = dObj.postalCode;
        applySelectedRegion();
    }

    function syncInlineSelects() {
        const pSel = document.getElementById('inlineProvinceSelect');
        const cSel = document.getElementById('inlineCitySelect');
        const dSel = document.getElementById('inlineDistrictSelect');
        if (pSel && cSel && dSel) {
            pSel.value = state.province;
            syncInlineCities();
            cSel.value = state.city;
            syncInlineDistricts();
            dSel.value = state.district;
        }
    }

    // Synchronize All Address Pieces to Full Address Text
    function syncCombinedAddress() {
        const street = document.getElementById('street_address').value.trim();
        const notes = document.getElementById('delivery_notes').value.trim();

        let parts = [];
        if (street) parts.push(street);
        if (state.subdistrict) parts.push('Kel. ' + state.subdistrict);
        if (state.district) parts.push('Kec. ' + state.district);
        if (state.city) parts.push(state.city);
        if (state.province) parts.push(state.province);
        if (state.postalCode) parts.push(state.postalCode);

        const fullAddress = parts.join(', ');
        document.getElementById('delivery_address').value = fullAddress;

        const previewEl = document.getElementById('combinedAddressPreview');
        if (fullAddress) {
            let noteExtra = notes ? ` <br><span class="text-[11px] text-[#5C6A57] font-semibold">ðŸ“ Patokan / Catatan: ${notes}</span>` : '';
            previewEl.innerHTML = `<span class="text-[#2A2626] font-medium">${fullAddress}</span>${noteExtra}`;
        } else {
            previewEl.textContent = 'Belum ada alamat yang dipilih.';
        }
    }

    // Geolocation / GPS helper for house pin
    function detectGpsLocation() {
        const btn = document.getElementById('gpsLocBtn');
        const msg = document.getElementById('gpsStatusMessage');
        msg.classList.remove('hidden');

        if (!navigator.geolocation) {
            msg.textContent = 'Fitur GPS tidak didukung di peramban ini.';
            msg.className = 'text-[10px] text-red-600 font-medium pt-0.5';
            return;
        }

        btn.disabled = true;
        msg.textContent = 'Mengakses koordinat satelit GPS lokasi rumah Anda...';
        msg.className = 'text-[10px] text-[#B85D66] font-medium pt-0.5 animate-pulse';

        navigator.geolocation.getCurrentPosition(
            (pos) => {
                const lat = pos.coords.latitude.toFixed(6);
                const lng = pos.coords.longitude.toFixed(6);
                const mapsUrl = `https://maps.google.com/?q=${lat},${lng}`;
                
                const notesInput = document.getElementById('delivery_notes');
                let current = notesInput.value.trim();
                if (!current.includes('Titik GPS')) {
                    notesInput.value = current ? `${current} | [Titik GPS: ${mapsUrl}]` : `[Titik GPS: ${mapsUrl}]`;
                }

                msg.textContent = `âœ“ Titik GPS berhasil dipasang (${lat}, ${lng})`;
                msg.className = 'text-[10px] text-[#5C6A57] font-semibold pt-0.5';
                btn.disabled = false;
                syncCombinedAddress();
            },
            (err) => {
                msg.textContent = 'Tidak dapat mengakses lokasi GPS: ' + (err.message || 'Izin ditolak');
                msg.className = 'text-[10px] text-red-600 font-medium pt-0.5';
                btn.disabled = false;
            },
            { enableHighAccuracy: true, timeout: 10000 }
        );
    }

    function updateDeliveryFee() {
        const checkedZone = document.querySelector('input[name="delivery_zone_id"]:checked');
        let fee = checkedZone ? parseFloat(checkedZone.getAttribute('data-fee')) || 0 : 35000;

        if (subtotal >= freeShippingMin) {
            fee = 0;
            document.getElementById('shippingFeeLabel').innerHTML = '<span class="text-[#5C6A57] font-bold">GRATIS</span>';
        } else {
            document.getElementById('shippingFeeLabel').textContent = 'Rp ' + fee.toLocaleString('id-ID');
        }

        const grandTotal = Math.max(0, subtotal - discount + fee);
        document.getElementById('grandTotalLabel').textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');
    }

    function fillAddress(name, phone, addr) {
        document.getElementById('recipient_name').value = name;
        document.getElementById('recipient_phone').value = phone;
        document.getElementById('street_address').value = addr;
        syncCombinedAddress();
    }

    document.addEventListener('DOMContentLoaded', () => {
        initInlineDropdowns();
        applySelectedRegion();
        updateDeliveryFee();
    });

