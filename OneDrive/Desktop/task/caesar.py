def caesar_encrypt(text, shift):
    result = ""
    for char in text:
        if char.isupper():
            result += chr((ord(char) - 65 + shift) % 26 + 65)
        elif char.islower():
            result += chr((ord(char) - 97 + shift) % 26 + 97)
        else:
            result += char  # Non-alphabetical characters stay the same
    return result

def caesar_decrypt(text, shift):
    return caesar_encrypt(text, -shift)

def main():
    print("=== Caesar Cipher ===")
    choice = input("Type 'e' to encrypt or 'd' to decrypt: ").lower()

    if choice not in ['e', 'd']:
        print("Invalid choice. Please enter 'e' or 'd'.")
        return

    message = input("Enter your message: ")
    try:
        shift = int(input("Enter shift value (integer): "))
    except ValueError:
        print("Shift must be an integer.")
        return

    if choice == 'e':
        encrypted = caesar_encrypt(message, shift)
        print("Encrypted message:", encrypted)
    else:
        decrypted = caesar_decrypt(message, shift)
        print("Decrypted message:", decrypted)

if __name__ == "__main__":
    main()
