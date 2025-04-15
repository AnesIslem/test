document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type_propriete');
    const extraFieldsContainer = document.getElementById('extra_fields');
    
    typeSelect.addEventListener('change', function() {
        updateExtraFields(this.value);
    });
    
    function updateExtraFields(propertyType) {
        let html = '';
        
        // Common fields for all property types
        const commonFields = `
            <div class="dynamic-field fade-in">
                <select name="chambres">
                    <option value="">Chambres</option>
                    <option value="1">1+</option>
                    <option value="2">2+</option>
                    <option value="3">3+</option>
                    <option value="4">4+</option>
                </select>
            </div>
        `;
        
        html += commonFields;
        
        // Property-specific fields
        switch(propertyType) {
            case 'lot':
            case 'terrain':
                html += `
                    <div class="dynamic-field fade-in">
                        <input type="number" name="surface" 
                               placeholder="Surface (m²)" 
                               min="1">
                    </div>
                `;
                break;
                
            case 'villa':
                html += `
                    <div class="dynamic-field fade-in">
                        <select name="etages">
                            <option value="">Étages</option>
                            <option value="1">RDC</option>
                            <option value="2">RDC+1</option>
                            <option value="3">RDC+2</option>
                        </select>
                    </div>
                    <div class="dynamic-field fade-in">
                        <select name="garage">
                            <option value="">Garage</option>
                            <option value="1">Oui</option>
                            <option value="0">Non</option>
                        </select>
                    </div>
                    <div class="dynamic-field fade-in">
                        <select name="jardin">
                            <option value="">Jardin</option>
                            <option value="1">Oui</option>
                            <option value="0">Non</option>
                        </select>
                    </div>
                `;
                break;
                
            case 'appartement':
                html += `
                    <div class="dynamic-field fade-in">
                        <select name="etage">
                            <option value="">Étage</option>
                            <option value="0">Rez-de-chaussée</option>
                            <option value="1">1er</option>
                            <option value="2">2ème</option>
                            <option value="3">3ème</option>
                        </select>
                    </div>
                    <div class="dynamic-field fade-in">
                        <select name="ascenseur">
                            <option value="">Ascenseur</option>
                            <option value="1">Oui</option>
                            <option value="0">Non</option>
                        </select>
                    </div>
                `;
                break;
                
            case 'maison':
                html += `
                    <div class="dynamic-field fade-in">
                        <select name="garage">
                            <option value="">Garage</option>
                            <option value="1">Oui</option>
                            <option value="0">Non</option>
                        </select>
                    </div>
                `;
                break;
        }
        
        extraFieldsContainer.innerHTML = html;
    }
});