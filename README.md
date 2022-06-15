# Info2-Kávé webshop

## Specifikáció
### Feladat informális leírása
A feladat célja, egy webshop elkészítése. A webshopon belül kétféle fiók hozható létre. 
- Készíthető felhasználói fiók, amely lehetővé tesz egy regisztrációt (illetve belépést, ha már regisztrált), ebben az esetben lehetőség nyílik a meglévő kínálatból vásárolni. A fiók szerkesztésére is van lehetőség, ahol email-cím/jelszó változtatás illetve maga a fiók törlése is elérhető.
- Ezen felül egy ú.n admin fiók is készíthető (ebben az esetben nem regisztráció szükséges, hanem az SQL-ban lévő fájl admin felhasználó beszúrása). Az admin képes a kínálat szerkesztésére
### A főoldalról elérhető oldalak és az azokon elérhető funkciók listája.
#### Nem regisztrált felhasználóknak
* `index.php` : Ez a főoldal, ahonnan nyílik két link a regisztrációs illetve a belépő felületre
* `coffee.php`: Az aktuális kínálat, ahol van lehetőség keresni (rendelni nincs) a termékek között
* `registration.php`: Itt van lehetőség regisztrálni
* `login.php`: Itt van lehetőség belépni, amennyiben már van fiókja
#### Regisztrált felhasználóknak 
* `index.php` : Ez a főoldal, ahonnan nyílik két link a regisztrációs illetve a belépő felületre
* `coffee.php`: Az aktuális kínálat, ahol van lehetőség keresni (rendelni is) a termékek között
* `welcome.php`: A fiók adatira mutató oldal (Profilom), itt lehet áttekinteni a regisztráció során megadott adatokat (jelszót nem), illetve a fiók szerkesztésére is itt adódik lehetőség
* `purchases.php`: Ahol a megvásárolt termékek látszódnak, amelyeket szerkeszteni is van lehetőség
#### Admin felhasználónak
A fentieken túl:
* `admin.php`: Ahol a kínálat szerkesztése elérhető
#### Adatbázis
Az adatbázisban a következő entitásokat és attribútumokat tároljuk:
* Vevő (Vevo): Vname (fiók felhasználó nevv), Vemail ( fiókhoz tartozó email-cím), Vpassword (fiók jelszava)
* Kávé (Cofee): Cname (kávé neve), Ctype (kávé őrőlési típusa), Cmanufact (kávé gyártója), Cplaceoforigin (kávé származási helye), Cdb (készleten lévő csomag(ok)), Cimage (kép a kávéról)
* Vásárlás (purchase): Pdb (vásárlások száma), vásárló, megvett termék
* A fenti adatok tárolását az alábbi sémával oldjuk meg:
* Linkek:
[kep1]:(https://github.com/DuT0mi/Container/blob/main/Info2WS/img/db_schema.png)
[kep2]:(https://github.com/DuT0mi/Container/blob/main/Info2WS/img/db_schema_2.png)
![kep1](https://github.com/DuT0mi/Container/blob/main/Info2WS/img/db_schema.png)
![kep2](https://github.com/DuT0mi/Container/blob/main/Info2WS/img/db_schema_2.png)
