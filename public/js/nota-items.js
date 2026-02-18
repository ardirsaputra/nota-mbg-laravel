// Shared Nota item behavior (used by create & edit views)
(function () {
    // Expect global arrays: barangList, satuanList, initialItems (optional)
    let rowCount = 0;
    let draggedItemRow = null;

    function buildSatuanOptions(selected) {
        selected = selected || "";
        const opts = (satuanList || []).map(
            (s) =>
                `<option value="${escapeHtml(s)}" ${s === selected ? "selected" : ""}>${escapeHtml(s)}</option>`,
        );
        if (!opts.find((o) => /value="Kg"/.test(o))) {
            opts.unshift(
                `<option value="Kg" ${selected === "Kg" ? "selected" : ""}>Kg</option>`,
            );
        }
        return opts.join("");
    }

    function escapeHtml(str) {
        return String(str || "").replace(
            /[&<>"]/g,
            (m) =>
                ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;" })[m],
        );
    }

    function formatRupiah(num) {
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 0,
        }).format(num || 0);
    }
    function parseRupiah(text) {
        return parseInt((text || "").toString().replace(/\D/g, "")) || 0;
    }

    // SEARCH
    let _searchActiveIndex = -1;
    function clearSearchResults() {
        const c = document.getElementById("searchResults");
        if (c) {
            c.innerHTML = "";
            c.style.display = "none";
        }
        _searchActiveIndex = -1;
    }
    function _bindSearchClickHandlers(c) {
        c.querySelectorAll(".search-item").forEach((el) => {
            el.addEventListener("click", () => {
                if (el.classList.contains("add-new")) {
                    // open modal prefilled
                    const v = el.dataset.uraian || "";
                    const modal = document.getElementById("addBarangModal");
                    const inp = document.getElementById("newUraian");
                    if (inp) inp.value = v;
                    if (modal) {
                        modal.style.display = "flex";
                        setTimeout(() => inp && inp.focus(), 50);
                    }
                    clearSearchResults();
                    return;
                }
                selectSearchItem(
                    el.dataset.uraian,
                    parseInt(el.dataset.harga) || 0,
                    el.dataset.satuan || "",
                );
            });
        });
    }
    function showSearchResults(q) {
        const c = document.getElementById("searchResults");
        if (!c) return;
        const qTrim = (q || "").trim();
        const qLower = qTrim.toLowerCase();
        if (!qLower) {
            clearSearchResults();
            return;
        }
        const results = (barangList || [])
            .filter((b) => b.uraian.toLowerCase().includes(qLower))
            .slice(0, 50);

        const canCreate = !!window.canCreateMasterBarang;
        if (results.length === 0) {
            c.innerHTML = `
                <div class="search-item">Tidak ada hasil</div>
                ${canCreate ? `<div class="search-item add-new" data-uraian="${escapeHtml(qTrim)}">+ Tambah barang baru: "${escapeHtml(qTrim)}"</div>` : ""}
            `;
            c.style.display = "block";
            _bindSearchClickHandlers(c);
            return;
        }

        // render results + an option to add-new using current query (only if allowed)
        c.innerHTML =
            results
                .map(
                    (r) =>
                        `<div class='search-item' data-uraian='${escapeHtml(r.uraian)}' data-harga='${r.harga_satuan}' data-satuan='${escapeHtml(r.satuan)}'>${escapeHtml(r.uraian)} (Rp ${r.harga_satuan.toLocaleString("id-ID")}) — ${escapeHtml(r.satuan)}</div>`,
                )
                .join("") +
            (canCreate
                ? `<div class="search-item add-new" data-uraian="${escapeHtml(qTrim)}">+ Tambah barang baru: "${escapeHtml(qTrim)}"</div>`
                : "");

        c.style.display = "block";
        _bindSearchClickHandlers(c);
        _searchActiveIndex = -1;
    }
    function selectSearchItem(uraian, harga, satuan) {
        addRow(uraian, 1, harga, satuan, 0);
        clearSearchResults();
        const inp = document.getElementById("itemSearch");
        if (inp) inp.value = "";
    }

    // ROW CRUD
    function addRow(u = "", q = 1, h = 0, s = "", p = 0) {
        const tbody = document.getElementById("itemsBody");
        if (!tbody) return;
        const tr = document.createElement("tr");
        const rowId = "row_" + rowCount++;
        const canCreate = !!window.canCreateMasterBarang;
        let selectOptions =
            `<option value="">-- Pilih Barang atau Buat Baru --</option>` +
            (barangList || [])
                .map(
                    (b) =>
                        `<option value="${escapeHtml(b.uraian)}" data-harga="${b.harga_satuan}" data-satuan="${escapeHtml(b.satuan)}" data-profit="${b.profit_per_unit ?? 0}" ${u && b.uraian === u ? "selected" : ""}>${escapeHtml(b.uraian)} (Rp ${b.harga_satuan.toLocaleString("id-ID")} / ${escapeHtml(b.satuan)})</option>`,
                )
                .join("") +
            (canCreate
                ? `<option value="__custom__">+ Tambah Barang Baru</option>`
                : "");

        tr.id = rowId;
        tr.draggable = true;
        tr.innerHTML = `
      <td class="row-number" style="text-align:center;vertical-align:middle;">&nbsp;</td>
      <td style="text-align:center;vertical-align:middle;"><span class="drag-handle-item"><i class="fas fa-grip-vertical"></i></span></td>
      <td><select class="barang-select">${selectOptions}</select></td>
      <td><select class="satuan-select">${buildSatuanOptions(s || "Kg")}</select></td>
      <td><input type="number" step="0.01" value="${q}" class="qty text-right" onchange="updateRow('${rowId}')"></td>
      <td><input type="number" step="1" value="${p}" class="profit-per-unit text-right" onchange="updateRow('${rowId}')"></td>
      <td><input type="number" step="1" value="${h}" class="harga text-right" onchange="updateRow('${rowId}')"></td>
      <td class="subtotal text-right">Rp 0</td>
      <td style="text-align:center;vertical-align:middle;"><button type="button" class="btn-remove" onclick="removeRow('${rowId}')"><i class="fas fa-trash"></i></button></td>
    `;

        // events
        tr.addEventListener("dragstart", handleItemDragStart);
        tr.addEventListener("dragover", handleItemDragOver);
        tr.addEventListener("drop", handleItemDrop);
        tr.addEventListener("dragend", handleItemDragEnd);
        tr.addEventListener("dragenter", handleItemDragEnter);
        tr.addEventListener("dragleave", handleItemDragLeave);

        tbody.appendChild(tr);
        updateRowNumbers();

        const sel = tr.querySelector(".barang-select");
        sel.addEventListener("change", function () {
            onBarangSelect(this);
        });

        if (u) {
            sel.value = u;
            const opt = sel.options[sel.selectedIndex];
            const satuanFromOpt = opt
                ? opt.getAttribute("data-satuan") || "Kg"
                : "Kg";
            const satuanSel = tr.querySelector(".satuan-select");
            if (satuanSel) satuanSel.value = satuanFromOpt;
        }
        if (s) {
            const satuanSel2 = tr.querySelector(".satuan-select");
            if (satuanSel2) satuanSel2.value = s;
        }
        updateRow(rowId);
    }

    function onBarangSelect(sel) {
        if (sel.value === "__custom__") {
            if (!window.canCreateMasterBarang) {
                alert("Hanya admin yang dapat menambahkan barang master.");
                sel.value = "";
                return;
            }
            // open modal if exists and focus input
            const modal = document.getElementById("addBarangModal");
            const inp = document.getElementById("newUraian");
            if (modal) {
                modal.style.display = "flex";
                setTimeout(() => inp && inp.focus(), 50);
            }
            sel.value = "";
            return;
        }
        const option = sel.options[sel.selectedIndex];
        const harga = parseInt(option.getAttribute("data-harga")) || 0;
        const satuan = option.getAttribute("data-satuan") || "Kg";
        const profit = parseInt(option.getAttribute("data-profit")) || 0;
        const row = sel.closest("tr");
        row.querySelector(".harga").value = harga;
        const satuanSel = row.querySelector(".satuan-select");
        if (satuanSel) satuanSel.value = satuan;
        const profitInp = row.querySelector(".profit-per-unit");
        if (profitInp) profitInp.value = profit;
        updateRow(row.id);
    }

    function updateRow(rowId) {
        const row = document.getElementById(rowId);
        if (!row) return;
        const q = parseFloat(row.querySelector(".qty").value) || 0;
        const h = parseInt(row.querySelector(".harga").value) || 0;
        const sub = q * h;
        row.querySelector(".subtotal").textContent = formatRupiah(sub);
        updateTotal();
    }

    function updateRowNumbers() {
        const rows = document.querySelectorAll("#itemsBody tr");
        rows.forEach((r, idx) => {
            const numCell = r.querySelector(".row-number");
            if (numCell) numCell.textContent = idx + 1;
        });
    }

    function removeRow(rowId) {
        const el = document.getElementById(rowId);
        if (el) el.remove();
        updateRowNumbers();
        updateTotal();
    }
    function updateTotal() {
        let total = 0;
        document.querySelectorAll("#itemsBody tr").forEach((tr) => {
            const subText = tr.querySelector(".subtotal").textContent;
            total += parseRupiah(subText);
        });
        const el = document.getElementById("totalAmount");
        if (el) el.textContent = formatRupiah(total);
    }

    // Drag handlers
    function handleItemDragStart(e) {
        draggedItemRow = this;
        this.classList.add("dragging");
        e.dataTransfer.effectAllowed = "move";
    }
    function handleItemDragOver(e) {
        if (e.preventDefault) e.preventDefault();
        e.dataTransfer.dropEffect = "move";
        return false;
    }
    function handleItemDragEnter(e) {
        if (this !== draggedItemRow && this.tagName === draggedItemRow.tagName)
            this.classList.add("drag-over");
    }
    function handleItemDragLeave(e) {
        this.classList.remove("drag-over");
    }
    function handleItemDrop(e) {
        if (e.stopPropagation) e.stopPropagation();
        if (
            draggedItemRow !== this &&
            this.tagName === draggedItemRow.tagName
        ) {
            const tbody = document.getElementById("itemsBody");
            const allRows = Array.from(tbody.querySelectorAll("tr"));
            const draggedIndex = allRows.indexOf(draggedItemRow);
            const targetIndex = allRows.indexOf(this);
            if (draggedIndex < targetIndex)
                this.parentNode.insertBefore(draggedItemRow, this.nextSibling);
            else this.parentNode.insertBefore(draggedItemRow, this);
            updateRowNumbers();
        }
        return false;
    }
    function handleItemDragEnd(e) {
        this.classList.remove("dragging");
        const tbody = document.getElementById("itemsBody");
        const allRows = tbody.querySelectorAll("tr");
        allRows.forEach((r) => r.classList.remove("drag-over"));
        updateRowNumbers();
    }

    // Modal submit for new barang (tries AJAX store, falls back to optimistic client add)
    window.submitNewBarang = function () {
        const uraianEl = document.getElementById("newUraian");
        const satuanEl = document.getElementById("newSatuan");
        const hargaEl = document.getElementById("newHarga");
        const kategoriEl = document.getElementById("newKategori");
        const profitEl = document.getElementById("newProfitPerUnit");
        const uraian = (uraianEl?.value || "").trim();
        const satuan = (satuanEl?.value || "").trim();
        const harga = parseInt(hargaEl?.value) || 0;
        const kategori = (kategoriEl?.value || "Umum").trim();
        const profitPerUnit = parseInt(profitEl?.value) || 0;
        const uraianError = document.getElementById("uraianError");
        if (uraianError) {
            uraianError.style.display = "none";
            uraianError.textContent = "";
        }
        if (!uraian) {
            if (uraianError) {
                uraianError.textContent = "Nama barang tidak boleh kosong";
                uraianError.style.display = "block";
            }
            return;
        }
        if (
            barangList.some(
                (b) => b.uraian.toLowerCase() === uraian.toLowerCase(),
            )
        ) {
            if (uraianError) {
                uraianError.textContent =
                    "Barang sudah ada dalam daftar harga Anda";
                uraianError.style.display = "block";
            }
            return;
        }
        if (!satuan) {
            alert("Pilih satuan");
            return;
        }
        if (harga <= 0) {
            alert("Harga harus > 0");
            return;
        }

        const payload = {
            uraian: uraian,
            kategori: kategori,
            satuan: satuan,
            harga_satuan: harga,
            profit_per_unit: profitPerUnit,
        };

        const modal = document.getElementById("addBarangModal");
        const form = document.getElementById("addBarangForm");

        // Try AJAX POST to create master record (use per-user endpoint if set)
        fetch(window.barangStoreUrl || "/harga-barang-pokok", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]',
                ).content,
            },
            body: JSON.stringify(payload),
        })
            .then((r) => r.json())
            .then((data) => {
                if (data && data.status === "ok" && data.data) {
                    const created = data.data;
                    const newB = {
                        id: created.id,
                        uraian: created.uraian,
                        satuan: created.satuan,
                        harga_satuan: created.harga_satuan,
                        profit_per_unit: created.profit_per_unit || 0,
                    };
                    barangList.push(newB);
                    addRow(
                        created.uraian,
                        1,
                        created.harga_satuan,
                        created.satuan,
                        created.profit_per_unit || 0,
                    );
                } else {
                    // fallback optimistic add
                    const id = Math.floor(Math.random() * 1000000);
                    const newB = {
                        id: id,
                        uraian: uraian,
                        satuan: satuan,
                        harga_satuan: harga,
                    };
                    barangList.push(newB);
                    addRow(uraian, 1, harga, satuan);
                }
                if (modal) modal.style.display = "none";
                form?.reset();
            })
            .catch((err) => {
                console.error(err);
                // optimistic fallback
                const id = Math.floor(Math.random() * 1000000);
                const newB = {
                    id: id,
                    uraian: uraian,
                    satuan: satuan,
                    harga_satuan: harga,
                };
                barangList.push(newB);
                addRow(uraian, 1, harga, satuan);
                if (modal) modal.style.display = "none";
                form?.reset();
            });
    };

    // search wiring + initialization (runs immediately or when DOMContentLoaded fires)
    function _initNotaItems() {
        const input = document.getElementById("itemSearch");
        if (input) {
            input.addEventListener("input", () =>
                showSearchResults(input.value),
            );
            input.addEventListener("keydown", function (e) {
                const items = Array.from(
                    document.querySelectorAll("#searchResults .search-item"),
                );
                if (e.key === "Enter") {
                    e.preventDefault();
                    if (_searchActiveIndex >= 0 && items[_searchActiveIndex]) {
                        items[_searchActiveIndex].click();
                    } else {
                        const first = items[0];
                        if (first) first.click();
                    }
                } else if (e.key === "Escape") {
                    clearSearchResults();
                } else if (e.key === "ArrowDown") {
                    e.preventDefault();
                    if (!items.length) return;
                    _searchActiveIndex = Math.min(
                        (_searchActiveIndex === -1 ? -1 : _searchActiveIndex) +
                            1,
                        items.length - 1,
                    );
                    items.forEach((it, idx) =>
                        it.classList.toggle(
                            "active",
                            idx === _searchActiveIndex,
                        ),
                    );
                    items[_searchActiveIndex] &&
                        items[_searchActiveIndex].scrollIntoView({
                            block: "nearest",
                        });
                } else if (e.key === "ArrowUp") {
                    e.preventDefault();
                    if (!items.length) return;
                    _searchActiveIndex = Math.max(
                        (_searchActiveIndex === -1
                            ? items.length
                            : _searchActiveIndex) - 1,
                        0,
                    );
                    items.forEach((it, idx) =>
                        it.classList.toggle(
                            "active",
                            idx === _searchActiveIndex,
                        ),
                    );
                    items[_searchActiveIndex] &&
                        items[_searchActiveIndex].scrollIntoView({
                            block: "nearest",
                        });
                }
            });
            document.addEventListener("click", function (ev) {
                if (
                    !ev.target.closest ||
                    (!ev.target.closest("#itemSearch") &&
                        !ev.target.closest("#searchResults"))
                )
                    clearSearchResults();
            });
        }

        // initialize UI from initialItems if provided
        if (
            typeof initialItems !== "undefined" &&
            Array.isArray(initialItems)
        ) {
            initialItems.forEach((it) =>
                addRow(
                    it.uraian || "",
                    it.qty || 1,
                    it.harga || 0,
                    it.satuan || "Kg",
                    it.profit_per_unit || 0,
                ),
            );
        } else {
            // ensure at least one empty row
            if (document.querySelectorAll("#itemsBody tr").length === 0)
                addRow();
        }

        // form submit converts rows to hidden inputs
        const notaForm = document.getElementById("notaForm");
        if (notaForm) {
            notaForm.addEventListener("submit", function (e) {
                const rows = document.querySelectorAll("#itemsBody tr");
                if (rows.length === 0) {
                    e.preventDefault();
                    alert("Tambahkan minimal satu barang ke nota!");
                    return;
                }

                let hasValidItems = false;
                rows.forEach((row) => {
                    const select = row.querySelector(".barang-select");
                    const uraian = select ? select.value.trim() : "";
                    const qty =
                        parseFloat(row.querySelector(".qty").value) || 0;
                    const harga =
                        parseInt(row.querySelector(".harga").value) || 0;
                    const satuanSel = row.querySelector(".satuan-select");
                    const satuan = satuanSel ? satuanSel.value.trim() : "";

                    // Include any row that has an uraian (allow qty/harga to be zero or negative)
                    if (uraian) {
                        hasValidItems = true;
                        const h1 = document.createElement("input");
                        h1.type = "hidden";
                        h1.name = "uraian[]";
                        h1.value = uraian;
                        const h2 = document.createElement("input");
                        h2.type = "hidden";
                        h2.name = "qty[]";
                        h2.value = qty;
                        const h3 = document.createElement("input");
                        h3.type = "hidden";
                        h3.name = "harga[]";
                        h3.value = harga;
                        const h4 = document.createElement("input");
                        h4.type = "hidden";
                        h4.name = "satuan[]";
                        h4.value = satuan;
                        // profit per unit (optional)
                        const profitInp = document.createElement("input");
                        profitInp.type = "hidden";
                        profitInp.name = "profit_per_unit[]";
                        const profitValEl =
                            row.querySelector(".profit-per-unit");
                        profitInp.value = profitValEl
                            ? parseInt(profitValEl.value) || 0
                            : 0;

                        this.appendChild(h1);
                        this.appendChild(h2);
                        this.appendChild(h3);
                        this.appendChild(h4);
                        this.appendChild(profitInp);
                    }
                });

                if (!hasValidItems) {
                    e.preventDefault();
                    // highlight rows missing uraian
                    document
                        .querySelectorAll("#itemsBody tr")
                        .forEach(function (r) {
                            const sel = r.querySelector(".barang-select");
                            if (!sel || !sel.value || sel.value.trim() === "")
                                r.classList.add("missing-uraian");
                            else r.classList.remove("missing-uraian");
                        });
                    const firstMissing = document.querySelector(
                        "#itemsBody tr.missing-uraian",
                    );
                    if (firstMissing) {
                        firstMissing.scrollIntoView({ block: "center" });
                        const sel =
                            firstMissing.querySelector(".barang-select");
                        if (sel) sel.focus();
                    }
                    alert(
                        "Pastikan setiap item memiliki nama barang (uraian tidak boleh kosong).",
                    );
                    return;
                }
            });
        }
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", _initNotaItems);
    } else {
        _initNotaItems();
    }

    // expose functions globally used by inline attributes
    window.addRow = addRow;
    window.updateRow = updateRow;
    window.removeRow = removeRow;
    window.onBarangSelect = onBarangSelect;
    window.clearSearchResults = clearSearchResults;
    window.selectSearchItem = selectSearchItem;
})();
