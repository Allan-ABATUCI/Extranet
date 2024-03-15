CREATE OR REPLACE FUNCTION insert_into_periode_function()
RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO periode (id_composante , id_prestataire,annee,mois,jour_du_mois)
    VALUES (NEW.id_composante , NEW.id_prestataire,NEW.annee,NEW.mois,0);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;




