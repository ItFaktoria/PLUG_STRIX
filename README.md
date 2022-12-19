# Moduł Spingo - odrocz płatność dla Magento 2 w wersji 2.3.x, 2.4.x

## Spis treści

1. [Opis](#opis)
2. [Wymagania](#wymagania)
3. [Instalacja](#instalacja)
4. [Konfiguracja](#konfiguracja)
5. [Koszyk](#koszyk)

## Opis
Moduł płatności Spingo - odrocz płatność dodaje do Magento 2 opcję płatności Spingo.
Moduł współpracuje z Magento 2 w wersji 2.4.x oraz 2.3.x

Moduł dodaje następujące funkcjonalności
* Utworzenie płatności w sytemie odroczonej płatności Spingo
* Możliwość automatycznego odbierania notyfikacji z systemu płatności i zmianę statusu zamówienia
* Wyświetlenie metody płatności na stronie podsumowania zamówienia

## Wymagania
* Wersja PHP zgodna z wymaganiami zainstalowanej wersji Magento 2

## Instalacja
#### Kopiując pliki na serwer
1. Pobierz najnowszą wersję z [tu link][external-link-1]
2. Rozpakuj pobrany plik zip
3. Skopiuj zawartość do katalogu `app/code/Spingo` w głownym katalogu instalacja Magento2. **Jeżeli katalog nie istnieje - utwórz go.**

Po instalacji z poziomu konsoli uruchom:
* bin/magento module:enable Spingo_SpingoAdminUi Spingo_SpingoApi Spingo_SpingoCore Spingo_SpingoFrontendUi Spingo_SpingoWebApi
* bin/magento setup:upgrade
* bin/magento setup:di:compile
* bin/magento setup:static-content:deploy

## Konfiguracja
1. Przejdź do panelu administracynjego Magento 2.
2. Przejdź do  **Stores** -> **Configuration**.
3. Następnie **Sales** -> **Payment Methods**.
4. Sekcja **Spingo**
5. Aby zapisać wprowadzone zmiany należy wcisnąć przycisk **Save config**.

### Opcje ustawień
#### Główne ustawienia
| Parameter |                            |
|-----------|----------------------------|
| Włącz?    | Aktywuje metodę płatności. |

#### Ustawienia połączenia
| Parameter                    |                                                                                                       |
|------------------------------|-------------------------------------------------------------------------------------------------------|
| Tryb Sandbox?                | Aktywuje wtyczkę w trybie `sanbox`. (tryb testowy)                                                    |
| Wyślij powiadomienie         | Czy system Spingo ma wysyłać powiadomienia do Magento 2.                                              |
| Klucz API                    | Klucz API dostarczany przez Spingo. Parametr dostępny jednynie przy ustawieniu `Tryb Sanbox` na `nie` |
| Klucz API Sanbox             | Klucz API dostarczany przez Spingo. Parametr dostępny jednynie przy ustawieniu `Tryb Sanbox` na `tak` |
| Merchant ID                  | Nip klienta / sklepu                                                                                  |
| Contract ID                  | Identyfikator klienta / sklepu `nadawany przez Spingo`                                                |
| Adres powrotny               | Adres URL, na który będzie przekierowany klient po poprawnym procesowaniu wniosku                     |
| Adres anulowania             | Adres URL, na który będzie przekierowany klient po niepoprawnym procesowaniu wniosku                  |
| Logowanie operacji w koszyku | Adres URL, na który będzie przekierowany klient po niepoprawnym procesowaniu wniosku                  |

#### Ustawienia koszyka
| Parameter                     |                                                                                                             |
|-------------------------------|-------------------------------------------------------------------------------------------------------------|
| Minimalna wartość zamówienia  | Minimalna wartość zamówienia, przy której opcja płatności Spingo będzie widoczna w podsumowaniu zamówienia  |
| Maksymalna wartość zamówienia | Maksymalna wartość zamówienia, przy której opcja płatności Spingo będzie widoczna w podsumowaniu zamówienia |

## Koszyk
Metoda płatności Spingo - odrocz płatność wyświetli się w podsumowaniu zamówienia, gdy zostaną spełnione następujące warunki:
* kwota minimalna w koszyku osiągnie wartość ustawioną w konfiguracji metody płatności [Ustawienia koszyka](#Ustawienia koszyka)
* kwota maksymalna w koszyku nie osiągnie wartości ustawionej w konfiguracji metody płatności [Ustawienia koszyka](#Ustawienia koszyka)
* klient wprowadzi identyfikator NIP

#### Ustawienie widoczności identyfikatora NIP w checkout Magento 2
1. Przejdź do panelu administracynjego Magento 2.
2. Przejdź do  **Stores** -> **Configuration**.
3. Następnie **Customers** -> **Customer configuration** -> **Create New Account Options**.
4. **Show VAT Number on Storefront** = **yes**
5. Aby zapisać wprowadzone zmiany należy wcisnąć przycisk **Save config**.


<!--external links:-->
[external-link-1]: 
